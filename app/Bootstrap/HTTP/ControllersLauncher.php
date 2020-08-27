<?php
  namespace App\Bootstrap\HTTP;

  use App\Bootstrap\HTTP\Exceptions\BadRequestException;
  use App\Bootstrap\HTTP\Exceptions\ControllerLaunchException;
  use App\Bootstrap\HTTP\Exceptions\NotFoundException;
  use Exception;

  class ControllersLauncher {
  
    /**
     * @param Request $req
     * @param Response $res
     * @throws ControllerLaunchException
     * @noinspection PhpRedundantCatchClauseInspection
     */
    public static function launch(Request $req, Response $res) {
      try {
        $controllerClazz = self::findControllerClazz($req);
        
        if($controllerClazz === null) {
          throw new NotFoundException();
        }
        
        $instance = new $controllerClazz();
        
        if(!($instance instanceof Controller))
          throw new ControllerLaunchException("Controller class does not inherits from `App\Bootstrap\HTTP\Controller`.");
        
        try {
          $instance->onRequest($req, $res);
        } catch(NotFoundException $e) {
          $res->status(404);
          $res->plain($e->getMessage());
        } catch(BadRequestException $e) {
          $res->status(400);
          $res->plain($e->getMessage());
        } catch(Exception $e) {
          $res->status(500);
          throw $e; // to global exceptions handler
        }
        
      } catch(ControllerLaunchException $e) {
        throw $e;
      } catch(Exception $e) {
        throw new ControllerLaunchException("Cannot launch controller, unknown error", $e);
      }
    }
  
    private static function findControllerClazz(Request $req) {
      $dirUrl = $req->url->directoryUrl;
    
      if(isset(Route::$registry[$dirUrl])) {
        $map = Route::$registry[$dirUrl];
        if(isset($map[$req->method])) {
          return $map[$req->method];
        }
      }
    
      return null;
    }
    
  }