<?php
  declare(strict_types=1);
  namespace App\Bootstrap\Providers;

  use App\Bootstrap\Utils\ClassHelpers;
  use App\Bootstrap\Utils\Path;
  use Exception;
  use Monolog\Logger as MonologLogger;
  use Monolog\Handler\StreamHandler;
  
  class Log {
    public static MonologLogger $logger;
    private string $channelName = 'default';
    
    public function __construct() {
      $logFilePath = Path::resolve('/storage/logs/' . $this->channelName . '/log_' . date("d_m_Y"));
      $handler = new StreamHandler($logFilePath, MonologLogger::DEBUG);
      self::$logger = new MonologLogger($this->channelName);
      self::$logger->pushHandler($handler);
    }
  
    public static function debug(string $message, array $context = []) {
      self::$logger->debug($message, $context);
    }
    public static function info(string $message, array $context = []) {
      self::$logger->info($message, $context);
    }
    public static function warning(string $message, array $context = []) {
      self::$logger->warning($message, $context);
    }
    public static function error(string $message, array $context = []) {
      self::$logger->error($message, $context);
    }
    public static function captureException(Exception $e, array $context = []) {
      self::$logger->error(
          sprintf(
              'Logged exception %s: "%s" at %s line %s',
              ClassHelpers::getNameFromObject($e),
              $e->getMessage(), $e->getFile(), $e->getLine()
          ),
          array_merge(['exception' => $e], $context)
      );
    }
  
  }