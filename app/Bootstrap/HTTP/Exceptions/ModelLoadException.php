<?php
  declare(strict_types=1);
  namespace App\Bootstrap\HTTP\Exceptions;
  
  use Exception;

  class ModelLoadException extends Exception {
  
    public function __construct(string $modelName, Exception $previous = null) {
      parent::__construct( "Nie udało się wczytać modelu `{$modelName}`.", 0, $previous );
    }
  
  }