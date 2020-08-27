<?php
  namespace App\Bootstrap\Traits;
  
  use App\Bootstrap\Annotations\EventListenerAnnotation;
  use mindplay\annotations\Annotations;

  trait BuiltInAnnotations {
  
    protected function registerBuiltInAnnotations() {
      $manager = Annotations::getManager();
      $manager->registry[EventListenerAnnotation::getName()] = 'App\Bootstrap\Annotations\EventListenerAnnotation';
    }
  
  }