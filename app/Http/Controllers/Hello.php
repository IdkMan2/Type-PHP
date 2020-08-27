<?php
  namespace App\Http\Controllers;
  
  use App\Bootstrap\HTTP\Controller;
  use App\Bootstrap\HTTP\Request;
  use App\Bootstrap\HTTP\Response;

  class Hello implements Controller {
  
    public function onRequest(Request $req, Response $res) {
      return $res->status(200)->plain('Hello world');
    }
    
  }