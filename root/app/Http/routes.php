<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/',function(){return view('auth.login');});
Route::get('/mail/{name?}','AuthController@resendCode');
Route::get('/login',function(){return view('auth.login');});
Route::get('/register',function(){return view('auth.register');});
Route::get('/logout','AuthController@logout');
Route::get('/activeCode/{name?}',function($name){return view('auth.activeCode')->with('name', $name);});
Route::get('/home', 'HomeController@index');
Route::get('/post/delete/{id}','PostsController@destroy');
Route::get('/comment/delete/{id}','CommentsController@destroy');
Route::get('/comments/show/{id}','CommentsController@show');
Route::get('/profile/{name?}', 'HomeController@profile');
Route::post('/auth/login','AuthController@authenticate');
Route::post('/auth/register','AuthController@create');
Route::post('/auth/activeCode','AuthController@activate');
Route::post('/post/create','PostsController@store');
Route::post('/post/update/{id}','PostsController@update');
Route::post('/comment/add','CommentsController@store');
Route::post('/comment/update/{id}','CommentsController@update');
