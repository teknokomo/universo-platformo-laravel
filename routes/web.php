<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| All web routes return the SPA entry point (welcome view). Actual routing
| is handled client-side by the Vue application.
|
*/

Route::get('/{any?}', function () {
    return view('welcome');
})->where('any', '.*');
