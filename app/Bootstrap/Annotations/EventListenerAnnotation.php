<?php
  declare(strict_types=1);
  namespace App\Bootstrap\Annotations;
  
  use App\Bootstrap\Enums\EventListenerPriority;
  use BadMethodCallException;
  use InvalidArgumentException;
  use mindplay\annotations\IAnnotation;

  /**
   * Class EventListenerAnnotation
   * @package App\Bootstrap\Annotations
   * @usage('method'=>true, 'inherited'=>true)
   */
  class EventListenerAnnotation implements IAnnotation {
    public EventListenerPriority $priority;
    
    public function __construct() {
      $this->priority = EventListenerPriority::NORMAL();
    }
    
    public static function getName() {
      return 'eventListener';
    }
  
    public function initAnnotation(array $properties): void {
      if(sizeof($properties) > 0 && isset($properties[0])) {
        try {
          $this->priority = new EventListenerPriority($properties[0]);
        } catch(BadMethodCallException $e) {
          throw new InvalidArgumentException(
              '@EventListener annotation: `priority` argument doesn\'t instance of `EventListenerPriority` enum.'
          );
        }
        
      }
    }
    
  }