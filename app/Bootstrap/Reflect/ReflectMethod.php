<?php
  /** @noinspection PhpRedundantCatchClauseInspection */
  namespace App\Bootstrap\Reflect;
  
  use App\Bootstrap\Enums\ReflectExceptionReason;
  use App\Bootstrap\Exceptions\ReflectException;
  use App\Bootstrap\Interfaces\IReflect;
  use App\Bootstrap\Interfaces\IReflectAnnotations;
  use mindplay\annotations\AnnotationException;
  use mindplay\annotations\Annotations;
  use ReflectionException;
  use ReflectionMethod;

  /**
   * Class ReflectMethod
   * @package App\Bootstrap\Reflect
   */
  class ReflectMethod implements IReflect, IReflectAnnotations {
    private ReflectClass $rClass;
    private string $methodName;
    private ReflectionMethod $reflection;
  
    /**
     * ReflectClass constructor.
     * @param ReflectClass $rClass
     * @param string $methodName
     * @throws ReflectException
     */
    public function __construct(ReflectClass $rClass, string $methodName) {
      $this->rClass = $rClass;
      $this->methodName = $methodName;
      
      if(!$this->exists($rClass->getName(), $methodName)) {
        throw new ReflectException(ReflectExceptionReason::METHOD_DOES_NOT_EXISTS());
      }
  
      try {
        $this->reflection = new ReflectionMethod($rClass->getName(), $methodName);
      } catch(ReflectionException $e) {
        throw new ReflectException(ReflectExceptionReason::REFLECTION_EXCEPTION(), $e);
      }
    }
  
    /**
     * @return ReflectClass
     */
    public function getClass(): ReflectClass {
      return $this->rClass;
    }
  
    /**
     * @return string
     */
    public function getName(): string {
      return $this->methodName;
    }
  
    /**
     * @return ReflectionMethod
     */
    public function accessReflection(): ReflectionMethod {
      return $this->reflection;
    }
  
    /**
     * @return int
     */
    public function getParametersCount(): int {
      return $this->reflection->getNumberOfParameters();
    }
  
    /**
     * @return array
     */
    public function getParameters(): array {
      $params = [];
      foreach($this->reflection->getParameters() as $param) {
        array_push($params, new ReflectParam($this, $param));
      }
      return $params;
    }
  
    /**
     * @param int $position
     * @return ReflectParam
     */
    public function getParameter(int $position): ReflectParam {
      return new ReflectParam($this, $this->reflection->getParameters()[$position]);
    }
  
    /**
     * @return array
     * @throws ReflectException
     */
    public function getAnnotations(): array {
      try {
        return Annotations::ofMethod($this->rClass->accessReflection(), $this->methodName);
      } catch(AnnotationException $e) {
        throw new ReflectException(ReflectExceptionReason::ANNOTATION_EXCEPTION(), $e);
      }
    }
  
    /**
     * @param string $annotation
     * @return null
     * @throws ReflectException
     */
    public function getMetadata(string $annotation) {
      try {
        $annotations = Annotations::ofMethod($this->rClass->accessReflection(), $this->methodName, $annotation);
        
        if(sizeof($annotations) === 0)
          return null;
    
        return $annotations[0];
      } catch(ReflectionException $e) {
        throw new ReflectException(ReflectExceptionReason::REFLECTION_EXCEPTION(), $e);
      }
  
    }
  
    /**
     * @param string $class
     * @param string $methodName
     * @return bool
     */
    protected function exists(string $class, string $methodName): bool {
      return method_exists($class, $methodName);
    }
  
  }