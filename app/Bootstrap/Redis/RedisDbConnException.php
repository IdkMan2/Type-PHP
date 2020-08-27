<?php
  namespace App\Bootstrap\Redis;
  
  use Exception;

  class RedisDbConnException extends Exception {
  
    public function __construct($message="", Exception $previous = null) {
    
      parent::__construct(
        "Nie udało się połączyć z bazą: ".$message,
        0,
        $previous
      );
    }
  
  }