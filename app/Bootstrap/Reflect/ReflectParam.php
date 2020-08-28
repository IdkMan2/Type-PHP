<?php
  declare(strict_types=1);
  namespace App\Bootstrap\Reflect;
  
  use App\Bootstrap\Interfaces\IReflect;
  use ReflectionNamedType;
  use ReflectionParameter;
  use Reflector;

  /**
   * Class ReflectParam
   * @package App\Bootstrap\Reflect
   */
  class ReflectParam implements IReflect {
    private ReflectMethod $rMethod;
    private ReflectionParameter $reflection;
    
    public function __construct(ReflectMethod $rMethod, ReflectionParameter $reflection) {
      $this->rMethod = $rMethod;
      $this->reflection = $reflection;
    }
    
    public function getMethod(): ReflectMethod {
      return $this->rMethod;
    }
  
    public function getName(): string {
      return $this->reflection->getName();
    }
    
    public function getPosition(): int {
      return $this->reflection->getPosition();
    }
    
    public function getType() {
      return $this->reflection->hasType() ? $this->reflection->getType() : null;
    }
    
    public function getTypeName() {
      if($this->reflection->hasType()) {
        $type = $this->reflection->getType();
        if($type instanceof ReflectionNamedType) {
          return $type->getName();
        }
      }
      
      return null;
    }
  
    public function accessReflection(): Reflector {
      return $this->reflection;
    }
  }