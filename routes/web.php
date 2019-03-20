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
    return view('admin');
});


Route::group(['prefix' => 'appointments'], function () {
   Route::get('/', 'AppointmentController@store');
   Route::get('/search', 'AppointmentController@search');
   Route::get('/edit/{id}', 'AppointmentController@form_info')
       ->where('id', '[0-9]+');
   Route::post('/create', 'AppointmentController@create');
    Route::post('/update/{id}', 'AppointmentController@update')
        ->where('id', '[0-9]+');
   Route::post('/remove/{id}', 'AppointmentController@destroy')
       ->where('id', '[0-9]+');
});

Route::group(['prefix' => 'employees'], function () {
    Route::get('/', 'EmployeeController@store');
    Route::get('/search', 'EmployeeController@search');
    Route::get('/edit/{id}', 'EmployeeController@edit')
        ->where('id', '[0-9]+');
    Route::get('/form_info', 'EmployeeController@form_info');
    Route::post('/create', 'EmployeeController@create');
    Route::post('/update/{id}', 'EmployeeController@update')
        ->where('id', '[0-9]+');
    Route::post('/remove/{id}', 'EmployeeController@destroy')
        ->where('id', '[0-9]+');
});

Route::group(['prefix' => 'orders'], function () {
    Route::get('/', 'OrderController@store');
    Route::get('/search', 'OrderController@search');
    Route::get('/edit/{id}', 'OrderController@edit')
        ->where('id', '[0-9]+');
    Route::get('/form_info', 'OrderController@form_info');
    Route::post('/create', 'OrderController@create');
    Route::post('/update/{id}', 'OrderController@update')
        ->where('id', '[0-9]+');
    Route::post('/remove/{id}', 'OrderController@destroy')
        ->where('id', '[0-9]+');
});

Route::group(['prefix' => 'rooms'], function () {
    Route::get('/', 'RoomController@store');
    Route::get('/search', 'RoomController@search');
    Route::get('/edit/{id}', 'RoomController@form_info')
        ->where('id', '[0-9]+');
    Route::get('/info/{id}', 'RoomController@info')
        ->where('id', '[0-9]+');
    Route::post('/create', 'RoomController@create');
    Route::post('/update/{id}', 'RoomController@update')
        ->where('id', '[0-9]+');
    Route::post('/remove/{id}', 'RoomController@destroy')
        ->where('id', '[0-9]+');
});

Route::group(['prefix' => 'users'], function () {
    Route::get('/', 'UserController@store');
    Route::get('/search', 'UserController@search');
    Route::get('/edit/{id}', 'UserController@edit')
        ->where('id', '[0-9]+');
    Route::post('/create', 'UserController@create');
    Route::post('/update/{id}', 'UserController@update')
        ->where('id', '[0-9]+');
    Route::post('/remove/{id}', 'UserController@destroy')
        ->where('id', '[0-9]+');
});

Route::group(['prefix' => 'photos'], function () {
    Route::post('/upload', 'PhotoController@upload');
    Route::post('/remove/{id}', 'PhotoController@destroy')
        ->where('id', '[0-9]+');
    Route::post('/create', 'PhotoController@create');
    Route::get('/', 'PhotoController@store');
    Route::get('/search', 'PhotoController@search');
});
Auth::routes();

Route::post('login', 'UserController@signin');
Route::post('registration', 'UserController@create');

Route::get('/home', 'HomeController@index')->name('home');
