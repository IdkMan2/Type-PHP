<?php
  namespace App\Bootstrap\Exceptions;
  
  use App\Bootstrap\Enums\ListenerRegisterExceptionReason;
  use Exception;

  class ListenerRegisterException extends Exception {
    
    public function __construct(ListenerRegisterExceptionReason $reason, Exception $previous = null) {
      parent::__construct($reason, 0, $previous);
    }
  
  }