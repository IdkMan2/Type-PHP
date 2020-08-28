<?php
  declare(strict_types=1);
  namespace App\Bootstrap\HTTP;

  use App\Bootstrap\Utils\URL;
  use LogicException;

  class Route {
    static array $registry = [];
    
    public static function get(string $url, string $controller): void {
      self::putInRegistry($url, $controller);
    }
    
    public static function post(string $url, string $controller): void {
      self::putInRegistry($url, $controller);
    }
    
    public static function put(string $url, string $controller): void {
      self::putInRegistry($url, $controller);
    }
    
    public static function patch(string $url, string $controller): void {
      self::putInRegistry($url, $controller);
    }
    
    public static function delete(string $url, string $controller): void {
      self::putInRegistry($url, $controller);
    }
    
    private static function putInRegistry(string $url, string $controller): void {
      self::validateController($controller);
      
      $urlObject = new URL($url, $_SERVER['HTTP_HOST']);
      
      array_push(Route::$registry, [
        'levels' => $urlObject->levels,
        'controller' => $controller,
      ]);
    }
    
    private static function validateController(string $controllerClassName): void {
      if(!class_exists($controllerClassName))
        throw new LogicException("Controller class `{$controllerClassName}` doesn't exists.");
      
      $classInterfaces = class_implements($controllerClassName);
      
      if(
          !$classInterfaces
          || !array_search(Controller::class, $classInterfaces, true) !== false
      )
        throw new LogicException(
            "Controller class `{$controllerClassName}` doesn't implements `Controller` interface."
        );
    }
  
  }