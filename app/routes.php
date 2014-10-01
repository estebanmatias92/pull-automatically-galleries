<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::resource('users', 'UserController', ['only' => ['index', 'destroy']]);
Route::post('users', ['as' => 'users.connect', 'uses' => 'UserController@connect']);
Route::post('galleries', ['as' => 'galleries.update_list', 'uses' => 'GalleryController@updateList']);
Route::delete('galleries', ['as' => 'galleries.remove_list', 'uses' => 'GalleryController@destroyList']);
Route::get('/', function()
{
    return 'Hello!';
});
