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
    Route::post('update-plan/{id}', 'PlanController@updatePlan');
    Route::post('delete-plan', 'PlanController@deletePlan');
    Route::post('search-plan', 'PlanController@searchPlan');
    Route::post('status-plan/{id}', 'PlanController@statusPlan');

    //Brand Controller
    Route::get('brand', 'BrandController@index');
    Route::get('edit-brand/{id}', 'BrandController@editBrand');
    Route::post('create-brand', 'BrandController@createBrand');
    Route::post('update-brand/{id}', 'BrandController@updateBrand');
    Route::post('delete-brand', 'BrandController@deleteBrand');
    Route::post('search-brand', 'BrandController@searchBrand');

    //Category Controller
    Route::get('category', 'CategoryController@index');
    Route::get('edit-category/{id}', 'CategoryController@editCategory');
    Route::post('create-category', 'CategoryController@createCategory');
    Route::post('update-category/{id}', 'CategoryController@updateCategory');
    Route::post('delete-category', 'CategoryController@deleteCategory');
    Route::post('search-category', 'CategoryController@searchCategory');
    Route::post('status-category/{id}', 'CategoryController@statusCategory');
});

Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {
    Route::get('app', 'AppController@app');
});
