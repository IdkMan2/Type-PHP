<?php
  namespace App\Bootstrap\HTTP\Exceptions;
  
  use Exception;

  class NotFoundException extends Exception {
  
    public function __construct(
      string $message="Nie znaleziono żadnych zasobów pod żądnym adresem.",
      Exception $previous = null
    ) {
      parent::__construct($message, 0, $previous);
    }
  
  }