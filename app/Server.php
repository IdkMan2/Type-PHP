<?php
  namespace App;
  
  use App\Bootstrap\HTTP\Handler as HTTP;

  class Server extends Application {
    
    protected function onEnable() {
      parent::onEnable();
      
      // add http provider
      $this->addProviderByClass(HTTP::class);
    }
  
    protected function onPostEnabled() {
      parent::onPostEnabled();
  
      // init http request -> response process
      $http = $this->getProvider(HTTP::class);
      $http->proceed();
    }
  
  }