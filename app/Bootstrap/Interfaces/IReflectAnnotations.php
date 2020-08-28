<?php
  declare(strict_types=1);
  namespace App\Bootstrap\Interfaces;
  
  use mindplay\annotations\Annotation;

  interface IReflectAnnotations {
  
    /**
     * @return array
     */
    public function getAnnotations(): array;
  
    /**
     * @param string $annotation
     * @return Annotation | null
     */
    public function getMetadata(string $annotation);
    
  }