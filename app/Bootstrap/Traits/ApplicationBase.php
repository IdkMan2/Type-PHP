<?php
  namespace App\Bootstrap\Traits;
  
  use App\Bootstrap\Utils\Path;

  trait ApplicationBase {
    
    protected function createCacheDirIfNotExists() {
      $annotationsCachePath = Path::resolve('/cache/annotations');
      if(!file_exists($annotationsCachePath)) {
        mkdir($annotationsCachePath, 777, true);
      }
    }
    
  }