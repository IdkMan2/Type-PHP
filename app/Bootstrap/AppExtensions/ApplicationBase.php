<?php
  namespace App\Bootstrap\AppExtensions;
  
  use App\Bootstrap\Annotations\EventListenerAnnotation;
  use App\Bootstrap\Utils\Path;
  use mindplay\annotations\Annotations;

  trait ApplicationBase {
    
    protected function createCacheDirIfNotExists() {
      $annotationsCachePath = Path::resolve('/cache/annotations');
      if(!file_exists($annotationsCachePath)) {
        mkdir($annotationsCachePath, 777, true);
      }
    }
  
    protected function registerBuiltInAnnotations() {
      $manager = Annotations::getManager();
      $manager->registry[EventListenerAnnotation::getName()] = 'App\Bootstrap\Annotations\EventListenerAnnotation';
    }
    
  }