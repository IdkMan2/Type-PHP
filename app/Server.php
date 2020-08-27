<?php
  namespace App;
  
  use App\Bootstrap\HTTP\Handler as HTTP;
  use Exception;

  class Server extends Application {
  
    /**
     * @throws Exception
     */
    public function onEnable() {
      parent::onEnable();
  
      // init http
      /*$http = $this->addProvider(HTTP::class);
      $http->proceed();*/
    }
  
  }