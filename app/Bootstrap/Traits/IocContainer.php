<?php
  namespace App\Bootstrap\Traits;

  use LogicException;

  trait IocContainer {
    protected array $providersMap = [];
  
    public function addProviderByInstance(object $provider): object {
      $this->providersMap[get_class($provider)] = $provider;
      return $provider;
    }
    
    public function addProviderByClass(string $providerClass): object {
      if(!class_exists($providerClass))
        throw new LogicException('Cannot add provider. Class `' . $providerClass . '` doesn\'t exists.');
      
      //TODO: here instable logic
      $providerInstance = new $providerClass();
      
      $this->providersMap[$providerClass] = $providerInstance;
      
      return $providerInstance;
    }
    
    public function removeProvider(string $providerClass): bool {
      $success = isset($this->providersMap[$providerClass]);
      
      if($success)
        unset($this->providersMap[$providerClass]);
      
      return $success;
    }
    
    public function getProvider(string $provider) {
      return (
        isset($this->providersMap[$provider])
        ? $this->providersMap[$provider]
        : null
      );
    }
  
    public function isProviderDefined(string $providerClass): bool {
      return isset($this->providersMap[$providerClass]);
    }
    
  }