<?php

use App\Http\Controllers\RedirectController;
use App\Http\Controllers\UrlController;
use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*admin*/
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::get('/', [UrlController::class, 'create'])
    ->name('shorturl.url.index');

Route::post('url-store', [UrlController::class, 'store'])
    ->name('shorturl.url.store');

Route::get('/{code}', [RedirectController::class, 'redirect'])
    ->name('shorturl.redirect');


