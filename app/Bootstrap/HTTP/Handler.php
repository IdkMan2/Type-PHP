<?php
  /** @noinspection PhpRedundantCatchClauseInspection */
  declare(strict_types=1);
  namespace App\Bootstrap\HTTP;
  
  use App\Application;
  use App\Bootstrap\Utils\Path;
  use Exception;
  use App\Bootstrap\HTTP\Exceptions\BadRequestException;
  use App\Bootstrap\HTTP\Exceptions\NotFoundException;
  use RuntimeException;

  class Handler {
    private Application $app;
  
    /**
     * Handler constructor.
     * @param Application $app
     */
    public function __construct(Application $app) {
      $this->app = $app;
    }
  
    public function proceed(): void {
      $req = new Request();
      $res = new Response();
      
      include Path::resolve('/app/Http/web.php');
  
      if(sizeof($req->url->levels) > 0 && $req->url->levels[0] === 'api') {
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        header("Access-Control-Allow-Origin: *");
        header("Accept: application/json");
      }
      
      try {
        ControllersLauncher::launch($this->app, $req, $res);
      } catch(NotFoundException $e) {
        $res->status(404);
        $res->plain($e->getMessage());
      } catch(BadRequestException $e) {
        $res->status(400);
        $res->plain($e->getMessage());
      } catch(Exception $e) {
        $res->status(500);
        throw new RuntimeException('HTTP handler rejected with Exception', 0, $e);
      }
    }
  
  }