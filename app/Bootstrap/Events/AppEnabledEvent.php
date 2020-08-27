<?php
  namespace App\Bootstrap\Events;
  
  class AppEnabledEvent extends Event {
    
    public function __construct() {
      parent::__construct('AppEnabledEvent');
    }
  
  }