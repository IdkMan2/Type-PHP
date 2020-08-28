<?php
  declare(strict_types=1);
  namespace App\Bootstrap\Utils;

  class URL {
    public string $url, $host, $directoryUrl, $query;
    public array $levels = [], $allowedCompressionTypes = [], $variables = [];
    public bool $allowedCompression = false;
  
    /**
     * RequestHandler constructor.
     * @param string $url
     * @param string $host
     */
    public function __construct(string $url, string $host) {
      $this->url = $url;
      $this->host = $host;
      
      $this->prepareUrl();
      $this->prepareLevels();
      $this->checkAllowedCompression();
    }
  
    /**
     * @internal
     */
    private function prepareUrl(): void {
      // Utnij wszystkie tagi HTML'a
      $dirUrl = strip_tags($this->url);
      // Usuń wszystkie znaki specjalne HTML'a, tj. &amp;
      $dirUrl = html_entity_decode($dirUrl);
      // Wydziel część directory od query
      $urlParts = explode("?", $dirUrl);
      $this->directoryUrl = $urlParts[0];
      /*
        Usunięcie poprzedzającego slasha (/)
        if(strlen($this->directoryUrl) > 0 && $this->directoryUrl[0] === DIRECTORY_SEPARATOR) {
        $this->directoryUrl = substr($this->directoryUrl, 1);
      }*/
      $this->query = sizeof($urlParts) > 1 ? $urlParts[1] : "";
    }
  
    private function prepareLevels(): void {
      $stringParts = explode("/", $this->directoryUrl);
      foreach($stringParts as $stringPart) {
        if(strlen($stringPart) === 0)
          continue;
        
        if($stringPart[0] === ':')
          array_push(
              $this->variables,
              strtolower(substr($stringPart, 1))
          );
        
        array_push(
            $this->levels,
            strtolower($stringPart)
        );
      }
    }
  
    private function checkAllowedCompression(): void {
      if(isset($_SERVER["HTTP_ACCEPT_ENCODING"])) {
        $this->allowedCompression = true;
        $this->allowedCompressionTypes = explode(",", $_SERVER["HTTP_ACCEPT_ENCODING"]);
        for($i=0; $i<sizeof($this->allowedCompressionTypes); $i++) {
          $this->allowedCompressionTypes[$i] = trim($this->allowedCompressionTypes[$i]);
        }
      }
    }
  
  }