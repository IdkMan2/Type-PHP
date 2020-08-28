<?php
  declare(strict_types=1);
  namespace App\Bootstrap\Interfaces;
  
  /*
   * This interface is just to tell the developer that the implementing class is a listener.
   * Anyway this class is a double-check for ->addListener, so it must be implemented.
   */
  interface Listener {}