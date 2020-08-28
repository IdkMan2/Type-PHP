<?php
  declare(strict_types=1);
  namespace App\Bootstrap\Exceptions;
  
  use App\Bootstrap\Enums\ReflectExceptionReason;
  use Exception;

  class ReflectException extends Exception {
  
    public function __construct(ReflectExceptionReason $reason, Exception $previous = null) {
      parent::__construct($reason, 0, $previous);
    }
    
    public function getReason(): ReflectExceptionReason {
      return new ReflectExceptionReason($this->getMessage());
    }
  
  }