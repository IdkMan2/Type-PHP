<?php
  declare(strict_types=1);
  namespace App\Bootstrap\Providers;
  
  use App\Bootstrap\Utils\Path;
  use Dotenv\Dotenv;

  class Env {
    
    public static function configure() {
      $dotenv = Dotenv::createImmutable(Path::resolve('/'));
      $dotenv->load();
      $dotenv->required(['APP_ENV']);
      return $dotenv;
    }
  
  }