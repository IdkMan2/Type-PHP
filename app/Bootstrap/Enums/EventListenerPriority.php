<?php
  declare(strict_types=1);
  namespace App\Bootstrap\Enums;
  
  use Spatie\Enum\Enum;

  /**
   * Class EventListenerPriority
   * @package App\Bootstrap\Enums
   * @method static self LOWEST()
   * @method static self LOW()
   * @method static self NORMAL()
   * @method static self HIGH()
   * @method static self HIGHEST()
   * @method static self MONITOR()
   */
  class EventListenerPriority extends Enum {
    
  }