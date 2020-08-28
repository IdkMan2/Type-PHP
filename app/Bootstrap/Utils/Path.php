<?php
  namespace App\Bootstrap\Utils;
  
  use App\Application;

  class Path {
  
    public static function resolve(string $path): string {
      $normalizedUserPath = self::normalize($path);
      if(strlen($normalizedUserPath) > 0 && $normalizedUserPath[0] === DIRECTORY_SEPARATOR)
        $normalizedUserPath = substr($normalizedUserPath, 1);
  
      return Application::DIRECTORY . self::normalize('/../') . $normalizedUserPath;
    }
    
    public static function normalize(string $path): string {
      $fullPath = str_replace('/', DIRECTORY_SEPARATOR, $path);
      $fullPath = str_replace('\\', DIRECTORY_SEPARATOR, $fullPath);
      return $fullPath;
    }
    
  }