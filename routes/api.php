<?php

Route::apiResources([
    'user' => '\App\Http\Controllers\Api\UserController',
]);

Route::get('findUser', '\App\Http\Controllers\Api\UserController@search');
Route::get('profile', '\App\Http\Controllers\Api\UserController@profile');
Route::put('profile', '\App\Http\Controllers\Api\UserController@updateProfile');
