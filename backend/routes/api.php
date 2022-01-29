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

    //Sub Category Controller
    Route::get('sub-category', 'SubCategoryController@index');
    Route::get('category-list', 'SubCategoryController@categoryList');
    Route::get('edit-sub-category/{id}', 'SubCategoryController@editSubCategory');
    Route::post('create-sub-category', 'SubCategoryController@createSubCategory');
    Route::post('update-sub-category/{id}', 'SubCategoryController@updateSubCategory');
    Route::post('delete-sub-category', 'SubCategoryController@deleteSubCategory');
    Route::post('search-sub-category', 'SubCategoryController@searchSubCategory');
    Route::post('status-sub-category/{id}', 'SubCategoryController@statusSubCategory');

    //Child Category Controller
    Route::get('child-category', 'ChildCategoryController@index');
    Route::get('category-list', 'ChildCategoryController@categoryList');
    Route::get('sub-category-list/{id}', 'ChildCategoryController@subCategoryList');
    Route::get('edit-child-category/{id}', 'ChildCategoryController@editChildCategory');
    Route::post('create-child-category', 'ChildCategoryController@createChildCategory');
    Route::post('update-child-category/{id}', 'ChildCategoryController@updateChildCategory');
    Route::post('delete-child-category', 'ChildCategoryController@deleteChildCategory');
    Route::post('search-child-category', 'ChildCategoryController@searchChildCategory');
    Route::post('status-child-category/{id}', 'ChildCategoryController@statusChildCategory');

    //Material Controller
    Route::get('material', 'MaterialController@index');
    Route::get('edit-material/{id}', 'MaterialController@editMaterial');
    Route::post('create-material', 'MaterialController@createMaterial');
    Route::post('update-material/{id}', 'MaterialController@updateMaterial');
    Route::post('delete-material', 'MaterialController@deleteMaterial');
    Route::post('search-material', 'MaterialController@searchMaterial');
    Route::post('status-material/{id}', 'MaterialController@statusMaterial');
});

Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {
    Route::get('app', 'AppController@app');
});
