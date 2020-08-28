<?php
  /** @noinspection PhpRedundantCatchClauseInspection */
  declare(strict_types=1);
  namespace App\Bootstrap\Reflect;
  
  use App\Bootstrap\Enums\ReflectExceptionReason;
  use App\Bootstrap\Exceptions\ReflectException;
  use App\Bootstrap\Interfaces\IReflect;
  use App\Bootstrap\Interfaces\IReflectAnnotations;
  use mindplay\annotations\Annotation;
  use mindplay\annotations\AnnotationException;
  use mindplay\annotations\Annotations;
  use ReflectionClass;
  use ReflectionException;
  use ReflectionMethod;

  /**
   * Class ReflectClass
   * @package App\Bootstrap\Reflect
   */
  class ReflectClass implements IReflect, IReflectAnnotations {
    private string $className;
    private ReflectionClass $reflection;
  
    /**
     * ReflectClass constructor.
     * @param string $className
     * @throws ReflectException
     */
    public function __construct(string $className) {
      $this->className = $className;
  
      if(!$this->exists($className))
        throw new ReflectException(ReflectExceptionReason::CLASS_DOES_NOT_EXISTS());
      
      try {
        $this->reflection = new ReflectionClass($className);
      } catch(ReflectionException $e) {
        throw new ReflectException(ReflectExceptionReason::REFLECTION_EXCEPTION(), $e);
      }
    }
  
    /**
     * @return string
     */
    public function getName(): string {
      return $this->className;
    }
  
    /**
     * @return ReflectionClass
     */
    public function accessReflection(): ReflectionClass {
      return $this->reflection;
    }
    
    public function hasConstructor(): bool {
      return $this->accessReflection()->getConstructor() !== null;
    }
  
    /**
     * @return ReflectMethod
     * @throws ReflectException
     */
    public function getConstructor(): ReflectMethod {
      if($this->hasConstructor())
        return new ReflectMethod($this, $this->accessReflection()->getConstructor()->getName());
      else
        throw new ReflectException(ReflectExceptionReason::CONSTRUCTOR_DOES_NOT_EXISTS());
    }
  
    /**
     * @return array
     */
    public function getParentClassesNames(): array {
      $parents = class_parents($this->className);
      if($parents) {
        $rClasses = [];
        foreach($parents as $parent) {
          array_push($rClasses, $parent); // ->getName() ???
        }
        return $rClasses;
      } else
        return [];
    }
  
    /**
     * @return array
     * @throws ReflectException
     */
    public function getParentClasses(): array {
      $parents = class_parents($this->className);
      if($parents) {
        $rClasses = [];
        foreach($parents as $parent) {
          array_push($rClasses, new ReflectClass($parent)); // ->getName() ???
        }
        return $rClasses;
      } else
        return [];
    }
  
    /**
     * @param int $parentIndex
     * @return string | null
     * @throws ReflectException
     */
    public function getParentClass(int $parentIndex) {
      $parentsClassNames = class_parents($this->className);
      $enumeratedParents = [];
      
      foreach($parentsClassNames as $k => $v) {
        array_push($enumeratedParents, $v);
      }
      
      if($enumeratedParents && sizeof($enumeratedParents) > $parentIndex)
        return new ReflectClass($enumeratedParents[$parentIndex]);
      else
        return null;
    }
  
    /**
     * @return array
     * @throws ReflectException
     */
    public function getMethods(): array {
      $methods = array_filter(
        $this->reflection->getMethods(),
        function (ReflectionMethod $value) {
          return $value->getModifiers() !== ReflectionMethod::IS_PRIVATE;
        }
      );
      $rMethods = [];
      foreach($methods as $method) {
        array_push($rMethods, new ReflectMethod($this, $method->getName()));
      }
      return $rMethods;
    }
  
    /**
     * @return array
     */
    public function getMethodsNames(): array {
      $methods = [];
      foreach($this->reflection->getMethods() as $method) {
        array_push($methods, $method->getName());
      }
      return $methods;
    }
    
    /**
     * @param string $methodName
     * @return bool
     */
    public function hasMethod(string $methodName): bool {
      return method_exists($this->className, $methodName);
    }
  
    /**
     * @param string $methodName
     * @return ReflectMethod
     * @throws ReflectException
     */
    public function getMethod(string $methodName): ReflectMethod {
      return new ReflectMethod($this, $methodName);
    }
  
    /**
     * @return array
     * @throws ReflectException
     */
    public function getAnnotations(): array {
      try {
        return Annotations::ofClass($this->reflection);
      } catch(AnnotationException $e) {
        throw new ReflectException(ReflectExceptionReason::ANNOTATION_EXCEPTION(), $e);
      }
    }
  
    /**
     * @param string $annotation
     * @return Annotation | null
     * @throws ReflectException
     */
    public function getMetadata(string $annotation) {
      try {
        $annotations = Annotations::ofClass($this->reflection, $annotation);
        if(sizeof($annotations) === 0)
          return null;
        
        return $annotations[0];
      } catch(ReflectionException $e) {
        throw new ReflectException(ReflectExceptionReason::REFLECTION_EXCEPTION(), $e);
      }
  
    }
  
    /**
     * @param string $class
     * @return bool
     */
    protected function exists(string $class): bool {
      return class_exists($class);
    }
    
  }