<?php
  declare(strict_types=1);
  namespace App\Bootstrap\AppExtensions;
  
  use App\Bootstrap\Annotations\EventListenerAnnotation;
  use App\Bootstrap\Enums\ListenerRegisterExceptionReason;
  use App\Bootstrap\Events\Event;
  use App\Bootstrap\Exceptions\ListenerRegisterException;
  use App\Bootstrap\Exceptions\ReflectException;
  use App\Bootstrap\Reflect\ReflectClass;
  use App\Bootstrap\Reflect\ReflectMethod;

  trait EventsManager {
    private array $listenersMap = [];
  
    public function emitEvent(Event $event): void {
      
      foreach($this->listenersMap as $eventName => $listenersArray) {
        if(!($event instanceof $eventName) && !(is_subclass_of($event, $eventName)))
          continue;
        
        foreach($listenersArray as $loopedListener) {
          $loopedListener['object']->{$loopedListener['method']}($event);
        }
      }
    }
  
    /**
     * @param object $listener
     * @throws ListenerRegisterException
     */
    public function registerListener(object $listener): void {
      try {
        $listenersData = $this->selectListeners($listener);
        
        foreach($listenersData as $listenerData) {
          // here missing try-catch is intentional - this is not a runtime exception
          // and it should be fixed at writing time
          $eventClass = $this->retrieveEventClassName($listener, $listenerData['method']);
  
          if(!isset($this->listenersMap[$eventClass]))
            $this->listenersMap[$eventClass] = [];
  
          array_push(
              $this->listenersMap[$eventClass],
              $listenerData
          );
        }
      } catch(ReflectException $e) {
        throw new ListenerRegisterException(
            ListenerRegisterExceptionReason::REFLECT_EXCEPTION(), $e
        );
      }
    }
    
    public function unregisterListener(object $listener, string $methodName): bool {
      foreach($this->listenersMap as $eventName => $listenersArray) {
        for($i=0; $i<sizeof($listenersArray); $i++) {
          $loopedListener = $listenersArray[$i];
          if(
            $loopedListener['object'] === $listener
            && $loopedListener['method'] === $methodName
          ) {
            unset($this->listenersMap[$eventName][$i]);
            return true;
          }
        }
      }
      return false;
    }
  
    /**
     * @param object $listener
     * @return array
     * @throws ReflectException
     */
    private function selectListeners(object $listener) {
      $rClass = new ReflectClass(get_class($listener));
      /**
       * @var ReflectMethod[] $methods
       */
      $rMethods = $rClass->getMethods();
      $listenersMethods = [];
      
      foreach($rMethods as $rMethod) {
        /**
         * @var EventListenerAnnotation $eventListener
         */
        $eventListener = $rMethod->getMetadata('@' . EventListenerAnnotation::getName());
        
        if(!$eventListener)
          continue;
  
        array_push(
            $listenersMethods,
            [
                'object' => $listener,
                'method' => $rMethod->getName(),
                'priority' => $eventListener->priority,
            ]
        );
      }
      
      return $listenersMethods;
    }
  
    /**
     * @param object $listener
     * @param string $methodName
     * @return string
     * @throws ListenerRegisterException
     */
    private function retrieveEventClassName(object $listener, string $methodName) {
      try {
        // get base class & target method
        $className = get_class($listener);
        $rClass = new ReflectClass($className);
        $rMethod = $rClass->getMethod($methodName);
        
        // check params count
        if($rMethod->getParametersCount() === 0) {
          throw new ListenerRegisterException(
              ListenerRegisterExceptionReason::INVALID_PARAMETERS_COUNT()
          );
        }
        
        // get first param and type
        $rParam = $rMethod->getParameter(0);
        $firstParamType = $rParam->getTypeName();
        
        // check first param type
        if($firstParamType === null) {
          throw new ListenerRegisterException(
              ListenerRegisterExceptionReason::INVALID_FIRST_PARAMETER()
          );
        }
        
        // find out class (event class) of first param type
        $eventClass = new ReflectClass($firstParamType);
        $eventRootClass = $eventClass->getParentClass(0);
        
        // Does the event class inherits from `Event` class?
        if($eventRootClass->getName() !== Event::class) {
          throw new ListenerRegisterException(
              ListenerRegisterExceptionReason::INVALID_FIRST_PARAMETER()
          );
        }
        
        // Return event name === event class
        return $eventClass->getName();
      } catch(ReflectException $e) {
        throw new ListenerRegisterException(
            ListenerRegisterExceptionReason::REFLECT_EXCEPTION(), $e
        );
      }
    }
    
  }