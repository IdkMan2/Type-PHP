<?php
  namespace App\Bootstrap\Interfaces;
  
  use Reflector;

  interface IReflect {
  
    /**
     * @return string
     */
    public function getName(): string;
  
    /**
     * @return Reflector
     */
    public function accessReflection(): Reflector;
    
  }