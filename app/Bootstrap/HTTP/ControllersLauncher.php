<?php
  declare(strict_types=1);
  namespace App\Bootstrap\HTTP;

  use App\Application;
  use App\Bootstrap\HTTP\Exceptions\NotFoundException;

  class ControllersLauncher {
  
    /**
     * @param Application $app
     * @param Request $req
     * @param Response $res
     * @throws NotFoundException
     */
    public static function launch(Application $app, Request $req, Response $res): void {
      $controllerClass = self::findControllerClass($req);
  
      if($controllerClass === null) {
        throw new NotFoundException();
      }
  
      $instance = $app->instantiateClass($controllerClass);
  
      $instance->onRequest($req, $res);
    }
  
    private static function findControllerClass(Request $req) {
      $levels = $req->url->levels;
      $levelsSize = sizeof($levels);
      
      foreach(Route::$registry as $pathData) {
        
        $pathLevels = $pathData['levels'];
        $pathLevelsSize = sizeof($pathLevels);
  
        if($pathLevelsSize !== $levelsSize)
          continue;
        
        if($pathLevelsSize === 0 && $levelsSize === 0)
          return $pathData['controller'];
        
        for($i=0; $i<$levelsSize; $i++) {
          $urlLevel = $levels[$i];
          $pathLevel = $pathLevels[$i];
          $isVariable = $pathLevel[0] === ':';
          
          if(!$isVariable && $urlLevel !== $pathLevel)
            continue 2;
        }
  
        return $pathData['controller'];
      }
    
      return null;
    }
    
  }