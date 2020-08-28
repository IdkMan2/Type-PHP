<?php
  declare(strict_types=1);
  namespace App\Bootstrap\Events;
  
  class AppShutdownEvent extends Event {
    
    public function __construct() {
      parent::__construct('AppShutdownEvent');
    }
  
  }