<?php
  declare(strict_types=1);
  namespace App\Bootstrap\HTTP\Exceptions;
  
  use Exception;

  class ModelSaveException extends Exception {
  
    public function __construct(string $modelName, Exception $previous = null) {
      parent::__construct( "Nie udało się zapisać modelu `{$modelName}`.", 0, $previous );
    }
  
  }