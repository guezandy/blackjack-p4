<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/createGame', 'HomeController@createGame')->name('createGame');
Route::get('/joinGame', 'HomeController@joinGame')->name('joinGame');
Route::post('/endGame', 'HomeController@endGame')->name('endGame');
Route::post('/deleteGame', 'HomeController@deleteGame')->name('deleteGame');

Route::post('/startHand', 'HomeController@startHand')->name('startHand');
Route::post('/gameAction', 'HomeController@gameAction')->name('gameAction');
