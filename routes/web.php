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
Route::get("/login", 'LoginController@index');
Route::post("/login", 'LoginController@login');
Route::get('/logout','LoginController@logout');
//Route::get('/', function () {
//    return view('welcome');
//});
Route::group(['prefix' => 'wx'], function () {
    Route::get("/", function(){
    	return view('wx');
    });
    require("wx.php");
});


Route::group(['middleware' => ['login.check']], function () {
    Route::get('/', 'IndexController@index');
    include('admin.php');
});

