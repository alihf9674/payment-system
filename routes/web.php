<?php

use Illuminate\Support\Facades\Route;

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

Route::get('home', function () {
    return view('home');
})->name('home');


Route::get('products', 'App\Http\Controllers\ProductController@index')->name('products.index');

Route::get('basket', 'App\Http\Controllers\BasketController@index')->name('basket.index');
Route::get('basket/add/{product}', 'App\Http\Controllers\BasketController@add')->name('basket.add');
Route::post('basket/update/{product}', 'App\Http\Controllers\BasketController@update')->name('basket.update');
