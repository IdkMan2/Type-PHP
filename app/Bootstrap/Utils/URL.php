<?php
  namespace App\Bootstrap\Utils;
  
  use Exception;

  class URL {
    public string $host, $originalUrl, $directoryUrl, $query;
    public array $levels = [], $allowedCompressionTypes = [];
    public bool $allowedCompression = false;
  
    /**
     * RequestHandler constructor.
     * @throws Exception
     */
    public function __construct() {
      $this->host = $_SERVER['HTTP_HOST'];
      $this->prepareUrl();
      $this->prepareLevels();
      $this->checkAllowedCompression();
    }
  
    /**
     * @internal
     * @throws Exception
     */
    private function prepareUrl() {
      $this->originalUrl = $_SERVER["REQUEST_URI"];
      // Utnij wszystkie tagi HTML'a
      $dirUrl = strip_tags($this->originalUrl);
      // Usuń wszystkie znaki specjalne HTML'a, tj. &amp;
      $dirUrl = html_entity_decode($dirUrl);
      /*
       * Encoduj znaki specjalne - nie używane
       * $this->formattedUrl = urldecode($formattedUrl);
       */
      $urlParts = explode("?", $dirUrl);
      $this->directoryUrl = $urlParts[0];
      /*
        Usunięcie poprzedzającego slasha (/)
        if(strlen($this->directoryUrl) > 0 && $this->directoryUrl[0] === DIRECTORY_SEPARATOR) {
        $this->directoryUrl = substr($this->directoryUrl, 1);
      }*/
      $this->query = sizeof($urlParts) > 1 ? $urlParts[1] : "";
    }
  
    private function prepareLevels() {
      $stringParts = explode("/", $this->directoryUrl);
      foreach($stringParts as $stringPart) {
        if($stringPart==="")
          continue;
        array_push($this->levels, mb_strtolower($stringPart, 'UTF-8'));
      }
      if(sizeof($this->levels) === 0) {
        array_push($this->levels, "index");
      }
    }
  
    private function checkAllowedCompression() {
      if(isset($_SERVER["HTTP_ACCEPT_ENCODING"])) {
        $this->allowedCompression = true;
        $this->allowedCompressionTypes = explode(",", $_SERVER["HTTP_ACCEPT_ENCODING"]);
        for($i=0; $i<sizeof($this->allowedCompressionTypes); $i++) {
          $this->allowedCompressionTypes[$i] = trim($this->allowedCompressionTypes[$i]);
        }
      }
    }
  
  }