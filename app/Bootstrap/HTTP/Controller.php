<?php
  declare(strict_types=1);
  namespace App\Bootstrap\HTTP;
  
  interface Controller {
    
    public function onRequest(Request $req, Response $res);
    
  }