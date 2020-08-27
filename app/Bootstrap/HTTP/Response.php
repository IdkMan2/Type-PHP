<?php
  namespace App\Bootstrap\HTTP;
  
  use App\Bootstrap\HTTP\Exceptions\ViewReadException;
  use RuntimeException;

  class Response {
  
    public function __construct() {
    }
    
    public function status(int $status) {
      http_response_code($status);
      return $this;
    }
    
    public function json(array $data) {
      header("Content-Type: application/json; charset=UTF-8");
      echo json_encode($data,JSON_PRETTY_PRINT);
      return $this;
    }
    
    public function plain(string $message) {
      header("Content-Type: text/plain; charset=UTF-8");
      echo $message;
      return $this;
    }
    
    public function header(string $name, string $value) {
      header($name . ": " . $value);
      return $this;
    }
  
    public function view(string $path, array $data=[]) {
      header("Content-Type: text/html; charset=UTF-8");
      try {
        TemplatesEngine::display($path, $data);
      } catch(ViewReadException $e) {
        throw new RuntimeException('Cannot display view', 0, $e);
      }
      return $this;
    }
  
  }