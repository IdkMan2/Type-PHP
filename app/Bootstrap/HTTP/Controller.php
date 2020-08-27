<?php
  namespace App\Bootstrap\HTTP;
  
  interface Controller {
    
    public function onRequest(Request $req, Response $res);
    
  }