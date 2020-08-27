<?php
  namespace App\Bootstrap\Providers;
  
  use App\Application;
  use Monolog\ErrorHandler;
  use RuntimeException;

  class GlobalErrorsHandler {
    static $errorsHandler;
    
    public function __construct(Application $app) {
      $logsServiceAvaiable = $app->isProviderDefined(Log::class);
      
      if(!$logsServiceAvaiable) {
        throw new RuntimeException(
            'Cannot setup global errors handler, because logs service is unavilable.'
        );
      }
      
      self::$errorsHandler = new ErrorHandler(Log::$logger);
    }
    
    public function setupHandlers() {
      self::$errorsHandler->registerErrorHandler();
      self::$errorsHandler->registerExceptionHandler();
      self::$errorsHandler->registerFatalHandler();
    }
  
  }