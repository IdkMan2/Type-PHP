<?php
  namespace App\Bootstrap\HTTP;

  class Route {
    static $registry = [];
    
    public static function get(string $name, $controller) {
      if(!isset(Route::$registry[$name])) {
        Route::$registry[$name] = [];
      }
      Route::$registry[$name]['GET'] = $controller;
    }
    
    public static function post(string $name, $controller) {
      if(!isset(Route::$registry[$name])) {
        Route::$registry[$name] = [];
      }
      Route::$registry[$name]['POST'] = $controller;
    }
    
    public static function put(string $name, $controller) {
      if(!isset(Route::$registry[$name])) {
        Route::$registry[$name] = [];
      }
      Route::$registry[$name]['PUT'] = $controller;
    }
    
    public static function patch(string $name, $controller) {
      if(!isset(Route::$registry[$name])) {
        Route::$registry[$name] = [];
      }
      Route::$registry[$name]['PATCH'] = $controller;
    }
    
    public static function delete(string $name, $controller) {
      if(!isset(Route::$registry[$name])) {
        Route::$registry[$name] = [];
      }
      Route::$registry[$name]['DELETE'] = $controller;
    }
  
  }