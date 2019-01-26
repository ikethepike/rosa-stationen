<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'user'], function () {
    Route::post('login', 'Api\StudentController@login');
    Route::post('register', 'Api\StudentController@register');
    Route::post('exists', 'Api\StudentController@exists');
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('lessons', 'LessonController@index');

    /* Resource routes */
    Route::group(['prefix' => 'resource'], function () {
        Route::resource('user', "Api\UserController");
    });

    Route::put('student/attendance', "Api\StudentController@markAttendance");
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
