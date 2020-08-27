<?php
  namespace App;

  use App\Bootstrap\Events\AppEnabledEvent;
  use App\Bootstrap\MySQL\MySqlDB;
  use App\Bootstrap\Providers\Log;
  use App\Bootstrap\Traits\ApplicationBase;
  use App\Bootstrap\Traits\BuiltInAnnotations;
  use App\Bootstrap\Traits\IocContainer;
  use App\Bootstrap\Traits\EventsManager;
  use App\Bootstrap\Utils\Env;
  use App\Bootstrap\Utils\Path;
  use Exception;
  use mindplay\annotations\AnnotationCache;
  use mindplay\annotations\Annotations;
  use Tracy\Debugger;

  class Application {
    use ApplicationBase, IocContainer, EventsManager, BuiltInAnnotations;
    
    const DIRECTORY = __DIR__;
  
    /**
     * Application constructor.
     * @throws Exception
     */
    public function __construct() {
      $this->onEnable();
      $this->postEnable();
    }
  
    /**
     * @throws Exception
     */
    protected function onEnable() {
      // organize directories etc.
      $this->createCacheDirIfNotExists();
      
      // setup annotations
      Annotations::$config['cache'] = new AnnotationCache(
          Path::resolve('/cache/annotations')
      );
      $this->registerBuiltInAnnotations();
      
      // configure environment variables
      Env::configure();
      $developmentMode = $_ENV['APP_ENV'] === 'development';
  
      // enable debugging in dev mode
      Debugger::$showBar = false;
      if($_ENV['APP_ENV'] === 'development') {
        Debugger::enable(
            $developmentMode ? Debugger::DEVELOPMENT : Debugger::PRODUCTION,
            Path::resolve('/storage/logs/exceptions')
        );
      }
      
      // setup logging
      $this->addProvider(Log::class);
      
      // define global errors handler
      /*$errorsHandler = $this->addProvider(GlobalErrorsHandler::class);
      $errorsHandler->setupHandlers();*/
      
      // init mysql database
      $mysqlDb = $this->addProvider(MySqlDB::class);
      $mysqlDb->open();
    }
  
    protected function postEnable() {
      $this->emitEvent(new AppEnabledEvent());
    }
  
    protected function onDisable() {
      // deinit mysql database
      if($this->isProviderDefined(MySqlDB::class))
        $this->getProvider(MySqlDB::class)->close();
    }
  
    function __destruct() {
      $this->onDisable();
    }
    
  }