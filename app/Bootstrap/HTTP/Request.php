<?php
  namespace App\Bootstrap\HTTP;
  
  use App\Bootstrap\Utils\URL;
  use App\Bootstrap\HTTP\Exceptions\BadRequestException;

  class Request {
    public $input, $files, $method, $url;
    
    public function __construct() {
      $requestData = file_get_contents("php://input");
      $dataArr = json_decode($requestData, true);
      $this->input = $dataArr === null ? $_GET : array_merge($_GET, $dataArr);
      $this->files = $_FILES;
      
      $this->method = mb_strtoupper($_SERVER["REQUEST_METHOD"], 'UTF-8');
      
      $this->url = new URL();
    }
  
    /**
     * @param array $attributes
     * @throws BadRequestException
     */
    public function required(array $attributes) {
      foreach($attributes as $attributeName) {
        if(!isset($this->input[$attributeName])) {
          throw new BadRequestException("Brak pola `{$attributeName}`.");
        }
      }
    }
  
  }