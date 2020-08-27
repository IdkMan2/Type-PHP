<?php
  namespace App\Bootstrap\Redis;
  
  use Exception;
  use Redis;
  use RuntimeException;

  /**
   * Class RedisDB
   */
  class RedisDB {
    /**
     * @var array $dbCredentials
     */
    private $dbCredentials;
    /**
     * @var Redis | null
     */
    private $conn = null;
  
    /**
     * RedisDB constructor.
     */
    public function __construct() {
      $this->dbCredentials = collect($_ENV)->only([
          'REDIS_HOST',
          'REDIS_PORT',
          'REDIS_PASSWORD',
          'REDIS_DB_INDEX',
          'REDIS_PREFIX'
      ])->toArray();
    }
  
    /**
     * @throws RedisDbConnException
     */
    public function open() {
      $this->conn = $this->getConnection();
    }
    
    public function isOpen(): bool {
      return $this->conn !== null;
    }
  
    public function close() {
      $this->conn->close();
      $this->conn = null;
    }
  
    /**
     * @param string $key
     * @param mixed $value
     * @param bool $withTTL
     */
    public function set(string $key, $value, bool $withTTL=false) {
      if( is_scalar( $value ) ) {
        $this->conn->set( $key, $value );
      } else {
        $this->conn->set( $key, json_encode($value) );
      }
      if($withTTL!==false) {
        if( gettype($withTTL)!=="integer" )
          throw new RuntimeException("Unknown TTL variable. Needs to be an integer.");
        $this->conn->expire( $key, $withTTL );
      }
    }
  
    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key) {
      $result = $this->conn->get($key);
      if($result===false)
        return null;
      else
        return $result;
    }
  
    /**
     * @param $key
     * @param $array
     * @param bool $withTTL
     * @throws Exception
     */
    public function setArray($key, $array, $withTTL=false ) {
      foreach( $array as $element ) {
        $this->conn->rPush( $key, $element );
      }
      if($withTTL!==false) {
        if( gettype($withTTL)!=="integer" )
          throw new Exception("Unknown TTL variable. Needs to be an integer.");
        $this->conn->expire( $key, $withTTL );
      }
    }
  
    /**
     * @param $key
     * @return mixed
     */
    public function getArray($key ) {
      $result = $this->conn->lRange($key, 0, -1);
      if($result === false)
        return null;
      return $result;
    }
  
    /**
     * @param $key
     * @param $ascArray
     * @param bool $withTTL
     * @throws Exception
     */
    public function setAscArray($key, $ascArray, $withTTL=false ) {
      $this->conn->hMSet( $key, $ascArray );
      if($withTTL!==false) {
        if( gettype($withTTL)!=="integer" )
          throw new Exception("Unknown TTL variable. Needs to be an integer.");
        $this->conn->expire( $key, $withTTL );
      }
    }
  
    /**
     * @param $key
     * @return mixed
     */
    public function getAscArray($key ) {
      $result = $this->conn->hGetAll($key);
      if($result===false)
        return null;
      else
        return $result;
    }
  
    /**
     * @param $key
     * @throws Exception
     */
    public function deleteKey($key ) {
      if( gettype($key)==="string" || gettype($key)==="array" ) {
        $this->conn->del($key);
      } else {
        throw new Exception("Unknown key variable. Needs to be a string or an array.");
      }
    }
  
    /**
     * @param $key
     * @param $ttl
     */
    public function setTimeoutOnKey($key, $ttl ) {
      $this->conn->expire( $key, $ttl );
    }
  
//    /**
//     * @param $dbNumber
//     */
//    public function switchDB($dbNumber ) {
//      $this->conn->select( $dbNumber );
//    }
  
    /**
     * @throws RedisDbConnException;
     * @return Redis
     */
    private function getConnection() {
      $conn = new Redis();
  
      $connectSuccess = $conn->connect(
          $this->dbCredentials['REDIS_HOST'],
          $this->dbCredentials['REDIS_PORT']
      );
      if(!$connectSuccess)
        throw new RedisDbConnException("Could not connect to host/redis db.");
  
      if(isset($this->dbCredentials['REDIS_PASSWORD'])) {
        $authSuccess = $conn->auth($this->dbCredentials['REDIS_PASSWORD']);
        if(!$authSuccess)
          throw new RedisDbConnException("Could not authenticate with redis db.");
      }
  
      $conn->setOption(Redis::OPT_PREFIX, $this->dbCredentials['REDIS_PREFIX']);
      $selectDBSuccess = $conn->select($this->dbCredentials['REDIS_DB_INDEX']);
  
      if(!$selectDBSuccess)
        throw new RedisDbConnException(
            "Cannot select db by index: " . $this->dbCredentials['REDIS_DB_INDEX']
        );
      
      return $conn;
    }
    
  }
























