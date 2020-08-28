<?php
  declare(strict_types=1);
  namespace App;

  use App\Bootstrap\Events\AppEnabledEvent;
  use App\Bootstrap\Events\AppShutdownEvent;
  use App\Bootstrap\MySQL\MySqlDB;
  use App\Bootstrap\Providers\Log;
  use App\Bootstrap\AppExtensions\ApplicationBase;
  use App\Bootstrap\AppExtensions\IocContainer;
  use App\Bootstrap\AppExtensions\EventsManager;
  use App\Bootstrap\Providers\Env;
  use App\Bootstrap\Utils\Path;
  use Exception;
  use mindplay\annotations\AnnotationCache;
  use mindplay\annotations\Annotations;
  use Tracy\Debugger;

  class Application {
    use ApplicationBase, IocContainer, EventsManager;
    
    const DIRECTORY = __DIR__;
  
    /**
     * Application constructor.
     * @throws Exception
     */
    public function __construct() {
      $this->onEnable();
      $this->onPostEnabled();
    }
  
    /**
     * @throws Exception
     */
    protected function onEnable() {
      // define Application class itself as a provider
      $this->addProviderByInstance($this);
      
      // organize directories etc.
      $this->createCacheDirIfNotExists();
      
      // setup annotations
      $annotationsCache = $this->addProviderByInstance(
          new AnnotationCache(
              Path::resolve('/cache/annotations')
          )
      );
      Annotations::$config['cache'] = $annotationsCache;
      $this->registerBuiltInAnnotations();
      
      // configure environment variables
      $this->addProviderByInstance(Env::configure());
      $developmentMode = $_ENV['APP_ENV'] === 'development';
  
      // enable debugging in dev mode OR error screen in production mode
      Debugger::$showBar = false;
      Debugger::$strictMode = true;
      Debugger::$scream = true;
      if($_ENV['APP_ENV'] === 'development') {
        Debugger::enable(
            $developmentMode ? Debugger::DEVELOPMENT : Debugger::PRODUCTION,
            Path::resolve('/storage/logs/exceptions')
        );
      }
      
      // setup logging
      $this->addProviderByClass(Log::class);
      
      // init mysql database
      $mysqlDb = $this->addProviderByClass(MySqlDB::class);
      $mysqlDb->open();
    }
    
    protected function onPostEnabled() {
      // emit app-enabled event
      $this->emitEvent(new AppEnabledEvent());
    }
    
    protected function onBeforeShutdown() {
      // emit app-shutdown event
      $this->emitEvent(new AppShutdownEvent());
    }
  
    protected function onDisable() {
      // deinit mysql database
      if($this->isProviderDefined(MySqlDB::class))
        $this->getProvider(MySqlDB::class)->close();
    }
  
    function __destruct() {
      $this->onBeforeShutdown();
      $this->onDisable();
    }
    
  }