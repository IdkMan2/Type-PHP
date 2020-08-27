<?php
  namespace App\Bootstrap\Utils;
  
  use Dotenv\Dotenv;

  class Env {
    
    public static function configure() {
      $dotenv = Dotenv::createImmutable(Path::resolve('/'));
      $dotenv->load();
      $dotenv->required(['MYSQL_HOST', 'MYSQL_USER', 'MYSQL_PASSWORD', 'MYSQL_DB_NAME']);
    }
  
  }