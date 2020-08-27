<?php
  namespace App\Bootstrap\Traits;

  trait IocContainer {
    protected array $providersMap = [];
  
    public function addProvider(string $provider): object {
      $providerInstance = new $provider();
      $this->providersMap[$provider] = $providerInstance;
      return $providerInstance;
    }
    
    public function removeProvider(string $provider): bool {
      $success = isset($this->providersMap[$provider]);
      
      if($success)
        unset($this->providersMap[$provider]);
      
      return $success;
    }
    
    public function getProvider(string $provider) {
      return (
        isset($this->providersMap[$provider])
        ? $this->providersMap[$provider]
        : null
      );
    }
  
    public function isProviderDefined(string $provider): bool {
      return isset($this->providersMap[$provider]);
    }
    
  }