<?php
  namespace App\Bootstrap\HTTP\Exceptions;
  
  use Exception;

  class ViewReadException extends Exception {
  
    public function __construct(string $message, Exception $previous = null) {
      parent::__construct( $message, 0, $previous );
    }
  
  }