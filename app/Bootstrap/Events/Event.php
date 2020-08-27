<?php
  namespace App\Bootstrap\Events;
  
  class Event {
    private string $name;
    
    public function __construct(string $eventName) {
      $this->name = $eventName;
    }
    
    public function getName() {
      return $this->name;
    }
    
  }