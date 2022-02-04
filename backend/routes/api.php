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
    Route::get('category-list', 'CategoryController@categoryList');
    Route::get('edit-category/{id}', 'CategoryController@editCategory');
    Route::post('create-category', 'CategoryController@createCategory');
    Route::post('update-category/{id}', 'CategoryController@updateCategory');
    Route::post('delete-category', 'CategoryController@deleteCategory');
    Route::post('search-category', 'CategoryController@searchCategory');
    Route::post('status-category/{id}', 'CategoryController@statusCategory');

    //Sub Category Controller
    Route::get('sub-category', 'SubCategoryController@index');
    Route::get('sub-category-list/{id}', 'SubCategoryController@subCategoryList');
    Route::get('edit-sub-category/{id}', 'SubCategoryController@editSubCategory');
    Route::post('create-sub-category', 'SubCategoryController@createSubCategory');
    Route::post('update-sub-category/{id}', 'SubCategoryController@updateSubCategory');
    Route::post('delete-sub-category', 'SubCategoryController@deleteSubCategory');
    Route::post('search-sub-category', 'SubCategoryController@searchSubCategory');
    Route::post('status-sub-category/{id}', 'SubCategoryController@statusSubCategory');

    //Child Category Controller
    Route::get('child-category', 'ChildCategoryController@index');
    Route::get('child-category-list/{id}', 'ChildCategoryController@childCategoryList');
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

    //Color Controller
    Route::get('color', 'ColorController@index');
    Route::get('edit-color/{id}', 'ColorController@editColor');
    Route::post('create-color', 'ColorController@createColor');
    Route::post('update-color/{id}', 'ColorController@updateColor');
    Route::post('delete-color', 'ColorController@deleteColor');
    Route::post('search-color', 'ColorController@searchColor');
    Route::post('status-color/{id}', 'ColorController@statusColor');

    //Size Controller
    Route::get('size', 'SizeController@index');
    Route::get('edit-size/{id}', 'SizeController@editSize');
    Route::post('create-size', 'SizeController@createSize');
    Route::post('update-size/{id}', 'SizeController@updateSize');
    Route::post('delete-size', 'SizeController@deleteSize');
    Route::post('search-size', 'SizeController@searchSize');
    Route::post('status-size/{id}', 'SizeController@statusSize');

    //Currency Controller
    Route::get('currency', 'CurrencyController@index');
    Route::get('edit-currency/{id}', 'CurrencyController@editCurrency');
    Route::post('create-currency', 'CurrencyController@createCurrency');
    Route::post('update-currency/{id}', 'CurrencyController@updateCurrency');
    Route::post('delete-currency', 'CurrencyController@deleteCurrency');
    Route::post('search-currency', 'CurrencyController@searchCurrency');
    Route::post('status-currency/{id}', 'CurrencyController@statusCurrency');
    Route::post('default-currency/{id}', 'CurrencyController@defaultCurrency');

    //Commission Controller
    Route::get('commission', 'CommissionController@index');
    Route::post('update-commission', 'CommissionController@updateCommission');

    //Country Controller
    Route::get('country', 'CountryController@index');
    Route::get('country-list', 'CountryController@countryList');
    Route::get('edit-country/{id}', 'CountryController@editCountry');
    Route::post('create-country', 'CountryController@createCountry');
    Route::post('update-country/{id}', 'CountryController@updateCountry');
    Route::post('delete-country', 'CountryController@deleteCountry');
    Route::post('search-country', 'CountryController@searchCountry');
    Route::post('status-country/{id}', 'CountryController@statusCountry');

    //State Controller
    Route::get('state', 'StateController@index');
    Route::get('state-list/{id}', 'StateController@stateList');
    Route::get('edit-state/{id}', 'StateController@editState');
    Route::post('create-state', 'StateController@createState');
    Route::post('update-state/{id}', 'StateController@updateState');
    Route::post('delete-state', 'StateController@deleteState');
    Route::post('search-state', 'StateController@searchState');
    Route::post('status-state/{id}', 'StateController@statusState');

    //City Controller
    Route::get('city', 'CityController@index');
    Route::get('city-list/{id}', 'CityController@cityList');
    Route::get('edit-city/{id}', 'CityController@editCity');
    Route::post('create-city', 'CityController@createCity');
    Route::post('update-city/{id}', 'CityController@updateCity');
    Route::post('delete-city', 'CityController@deleteCity');
    Route::post('search-city', 'CityController@searchCity');
    Route::post('status-city/{id}', 'CityController@statusCity');
});

Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {
    Route::get('app', 'AppController@app');
});
