<?php
  declare(strict_types=1);
  use App\Bootstrap\HTTP\Route;
  use App\Http\Controllers\Hello;
  
  Route::get('/', Hello::class);