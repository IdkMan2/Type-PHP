<?php
  declare(strict_types=1);
  namespace App\Bootstrap\Events;
  
  class AppEnabledEvent extends Event {
    
    public function __construct() {
      parent::__construct('AppEnabledEvent');
    }
  
  }