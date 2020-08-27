<?php
  namespace App\Bootstrap\HTTP;
  
  use App\Bootstrap\Utils\Path;
  use Exception;
  use App\Bootstrap\HTTP\Exceptions\ViewReadException;

  class TemplatesEngine {
  
    /**
     * @param string $completePath
     * @param array $data
     * @return string
     * @throws ViewReadException
     * @noinspection PhpUnusedParameterInspection
     */
    private static function toString(string $completePath, array $data=[]) {
      try {
        ob_start();
  
        if(!file_exists($completePath)) {
          throw new ViewReadException("File does not exists: {$completePath}");
        }
        
        $success = include $completePath;
        
        if(!$success) {
          throw new ViewReadException('Cannot include file.');
        }
        
        $bufferContent = ob_get_contents();
        ob_end_clean();
        
        if($bufferContent === false) {
          throw new ViewReadException('Buffer content returned false.');
        }
        
        return $bufferContent;
      } catch(Exception $e) {
        if($e instanceof ViewReadException)
          throw $e;
        else
          throw new ViewReadException("Cannot read view file, unknown error: {$completePath}", $e);
      }
    }
  
    private static function resolvePath(string $viewPath) {
      return Path::resolve(
          'app/Http/Views/' . str_replace(".", '/', $viewPath) . ".php"
      );
    }
  
    /**
     * @param string $viewPath
     * @param array $data
     * @throws ViewReadException
     */
    public static function display(string $viewPath, array $data) {
      echo self::toString(
          self::resolvePath($viewPath),
          $data
      );
    }
    
  }