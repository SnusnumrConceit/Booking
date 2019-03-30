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

Route::get('/admin', function () {
    return view('admin');
});


Route::group([
    'prefix' => 'appointments',
    'middleware' => ['auth', 'acl'],
    'is' => 'superadmin'
], function () {
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

Route::group([
    'prefix' => 'employees',
    'middleware' => ['auth', 'acl'],
    'is' => 'superadmin'
], function () {
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

Route::group([
    'prefix' => 'orders',
    'middleware' => ['auth', 'acl'],
    'is' => 'superadmin|admin|customer'
], function () {
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

Route::group([
    'prefix' => 'rooms'
], function () {
    Route::get('/', 'RoomController@store');
    Route::get('/public', 'RoomController@getPublicRooms');
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

Route::group([
    'prefix' => 'users',
    'is' => 'superadmin'
], function () {
    Route::get('/', 'UserController@store');
    Route::get('/search', 'UserController@search');
    Route::get('/edit/{id}', 'UserController@edit')
        ->where('id', '[0-9]+');
    Route::get('/form_info', 'UserController@form_info');
    Route::post('/create', 'UserController@create');
    Route::post('/update/{id}', 'UserController@update')
        ->where('id', '[0-9]+');
    Route::post('/remove/{id}', 'UserController@destroy')
        ->where('id', '[0-9]+');
});

Route::group([
    'prefix' => 'reports',
    'is' => 'superadmin|admin'
], function () {
    Route::get('/search', 'ReportController@search');
    Route::get('/edit/{id}', 'ReportController@edit')
        ->where('id', '[0-9]+');
    Route::get('/form_info', 'ReportController@form_info');
    Route::post('/create', 'ReportController@create');
    Route::post('/update/{id}', 'ReportController@update')
        ->where('id', '[0-9]+');
    Route::post('/remove/{id}', 'ReportController@destroy')
        ->where('id', '[0-9]+');
});

Route::get('/reports', 'ReportController@store');

Route::group([
    'prefix' => 'photos'
], function () {
    Route::post('/upload', 'PhotoController@upload');
    Route::post('/remove/{id}', 'PhotoController@destroy')
        ->where('id', '[0-9]+');
    Route::post('/create', 'PhotoController@create');
    Route::get('/', 'PhotoController@store');
    Route::get('/search', 'PhotoController@search');
    Route::get('/public', 'PhotoController@getPublicPhotos');
});
Auth::routes();

Route::post('login', 'UserController@signin');
Route::post('registration', 'UserController@registration');

Route::get('/', function () {
    return view('home');
});

Route::group(['middleware' => 'auth'], function () {
   Route::get('/users/info', 'UserController@info');
});