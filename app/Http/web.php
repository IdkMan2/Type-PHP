<?php
  use App\Bootstrap\HTTP\Route;
  use App\Http\Controllers\Hello;
  
  Route::get('/', Hello::class);