<?php
  declare(strict_types=1);
  namespace App\Bootstrap\AppExtensions;

  use App\Bootstrap\Exceptions\ReflectException;
  use App\Bootstrap\Reflect\ReflectClass;
  use LogicException;

  trait IocContainer {
    protected array $providersMap = [];
  
    /**
     * @param object $provider
     * @return object
     */
    public function addProviderByInstance(object $provider): object {
      $this->providersMap[get_class($provider)] = $provider;
      return $provider;
    }
  
    /**
     * @param string $providerClass
     * @return object
     */
    public function addProviderByClass(string $providerClass): object {
      $providerInstance = $this->instantiateClass($providerClass);
      
      $this->providersMap[$providerClass] = $providerInstance;
      
      return $providerInstance;
    }
  
    /**
     * @param string $providerClass
     * @return bool
     */
    public function removeProvider(string $providerClass): bool {
      $success = isset($this->providersMap[$providerClass]);
      
      if($success)
        unset($this->providersMap[$providerClass]);
      
      return $success;
    }
  
    /**
     * @param string $provider
     * @return mixed|null
     */
    public function getProvider(string $provider) {
      return (
        isset($this->providersMap[$provider])
        ? $this->providersMap[$provider]
        : null
      );
    }
  
    /**
     * @param string $providerClass
     * @return bool
     */
    public function isProviderDefined(string $providerClass): bool {
      return isset($this->providersMap[$providerClass]);
    }
    
    public function instantiateClass(string $className): object {
      if(!class_exists($className))
        throw new LogicException(
            "Cannot instantiate object from class `{$className}` - class doesn\'t exists."
        );
      
      $deps = $this->resolveClassDependecies($className);
      return new $className(...$deps);
    }
    
    private function resolveClassDependecies(string $class): array {
      try {
        $rClass = new ReflectClass($class);
        
        if(!$rClass->hasConstructor())
          return [];
        
        $constructorArgs = $rClass->getConstructor()->getParameters();
        $constructorDeps = [];
        
        foreach($constructorArgs as $constructorArg) {
          $argName = $constructorArg->getName();
          $argPos = $constructorArg->getPosition() + 1;
          $argType = $constructorArg->getTypeName();
          
          if($argType === null) {
            throw new LogicException(
                "Class `{$class}` has an argument `{$argName}` "
                . "(on position {$argPos}) that is missing type declaration."
            );
          }
          if(!class_exists($argType)) {
            throw new LogicException(
                "Class `{$class}` has an argument `{$argName}` "
                . "(on position {$argPos}) which type declaration doesn't refer to any known class ({$argType})."
            );
          }
          
          if(isset($this->providersMap[$argType])) {
            array_push($constructorDeps, $this->providersMap[$argType]);
            continue;
          } else {
            $closestParent = null;
            
            foreach($this->providersMap as $providerClass => $providerInstance) {
              $providerParents = class_parents($providerClass);
              if($providerParents) {
                foreach($providerParents as $providerParent) {
                  if($providerParent === $argType) {
                    array_push($constructorDeps, $providerInstance);
                    continue 3;
                  }
                }
              }
            }
          }
  
          throw new LogicException(
              "Class `{$class}` has an argument `{$argName}` "
              . "(on position {$argPos}) which type declaration doesn't refer to any registered provider ({$argType})."
          );
        }
        
        return $constructorDeps;
      } catch(ReflectException $e) {
        throw new LogicException("Looks like a class `{$class}` has invalid constructor arguments. System cannot inject dependencies.");
      }
    }
    
  }