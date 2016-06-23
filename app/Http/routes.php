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
Route::get('/log_in', 'UserController@login');
Route::post('/check_login', 'UserController@handleLogin');
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('template.index');
    });
    Route::get('image', 'UserController@getImage');
    Route::get('/logout', 'UserController@logout');

    //user route

    Route::get('/view_profile/{id}', 'UserController@viewProfile');
    Route::post('/update_profile', 'UserController@updateProfile');
    Route::get('/user_registration', ['as' => 'create_user', 'uses' => 'UserController@userRegistration']);
    Route::post('/change_user_name', ['as' => 'edit_user_name', 'uses' => 'UserController@changeUserName']);
    Route::post('/change_user_password', ['as' => 'edit_user_password', 'uses' => 'UserController@changeUserPassword']);
    Route::get('/all_user', ['as' => 'all_user', 'uses' => 'UserController@getAllUser']);
    Route::post('handle_registration', 'UserController@handleRegister');
    Route::post('update_permission/{id}', 'UserController@updatePermission');
    Route::get('/user_management', ['as' => 'view_user_list', 'uses' => 'UserController@userManagement']);
    Route::get('/edit_user/{id}', ['as' => 'edit_user', 'uses' => 'UserController@editUser']);
    Route::post('/block_user', ['as' => 'block_user', 'uses' => 'UserController@blockUser']);
    Route::post('/unblock_user', ['as' => 'unblock_user', 'uses' => 'UserController@unBlockUser']);
    Route::get('/edit_user_permission/{id}', ['as' => 'edit_user_permission', 'uses' => 'UserController@editUserPermission']);
    Route::get('/user_search', ['as' => 'user_search', 'uses' => 'UserController@userSearch']);
    Route::post('change_user_image', 'UserController@changeUserImage');

    //end user route
});
