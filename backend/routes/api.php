<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'api', 'namespace' => 'App\Http\Controllers\Api'], function () {

    // Frontend API
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('user', 'AuthController@user');

    //Plan Controller
    Route::get('plan', 'PlanController@index');
    Route::get('edit-plan/{id}', 'PlanController@editPlan');
    Route::post('create-plan', 'PlanController@createPlan');
    Route::post('update-plan/{id}', 'PlanController@updateePlan');
    Route::post('delete-plan', 'PlanController@deletePlan');
});

Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {
    Route::get('app', 'AppController@app');
});
