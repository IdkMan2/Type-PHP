<?php
  declare(strict_types=1);
  namespace App\Bootstrap\MySQL;
  
  use Exception;

  class MySqlDbConnException extends Exception {
  
    public function __construct($message="", Exception $previous = null) {
    
      parent::__construct(
        "Nie udało się połączyć z bazą: ".$message,
        0,
        $previous
      );
    }
  
  }