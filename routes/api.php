<?php

Route::apiResources([
    'user' => '\App\Http\Controllers\Api\UserController',
]);

Route::get('profile', '\App\Http\Controllers\Api\UserController@profile');
Route::put('profile', '\App\Http\Controllers\Api\UserController@updateProfile');
