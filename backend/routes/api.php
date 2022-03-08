<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'api', 'namespace' => 'App\Http\Controllers\Api'], function () {

    // Frontend API
    Route::post('login', 'AuthController@login');
    Route::post('dashboard-login', 'AuthController@dashboardLogin');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('user', 'AuthController@user');

    //Seller Controller
    Route::get('store-all', 'StoreController@index');
    Route::get('store-active', 'StoreController@storeActive');
    Route::get('store-pending', 'StoreController@storePending');
    Route::get('store-suspend', 'StoreController@storeSuspend');
    Route::get('pending-store', 'StoreController@pendingStore');
    Route::post('search-store', 'StoreController@searchStore');
    Route::get('edit-store/{id}', 'StoreController@editStore');
    Route::post('seller-regestration', 'StoreController@sellerRegestration');
    Route::post('change-store-status', 'StoreController@changeStoreStatus');
    Route::post('store-status/{id}', 'StoreController@storeStatus');
    Route::post('update-store/{id}', 'StoreController@updateStore');

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
    Route::get('brand-list', 'BrandController@brandList');
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
    Route::post('search-commission', 'CommissionController@searchCommission');
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
    Route::get('state-rules', 'StateController@stateRules');
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

    //Shipping Cost Controller
    Route::get('shipping-cost', 'ShippingCostController@index');
    Route::get('shipping-cost-list', 'ShippingCostController@shippingCostList');
    Route::get('edit-shipping-cost/{id}', 'ShippingCostController@editShippingCost');
    Route::post('create-shipping-cost', 'ShippingCostController@createShippingCost');
    Route::post('update-shipping-cost/{id}', 'ShippingCostController@updateShippingCost');
    Route::post('delete-shipping-cost', 'ShippingCostController@deleteShippingCost');
    Route::post('search-shipping-cost', 'ShippingCostController@searchShippingCost');
    Route::post('update-shipping-cost-rules', 'ShippingCostController@updateShippingCostRules');

    //Request Controller
    Route::get('request/{status}', 'RequestController@index');
    Route::get('pending-request', 'RequestController@myRequest');
    Route::get('my-request', 'RequestController@myRequest');
    Route::post('approve-request/{id}', 'RequestController@approveRequest');
    Route::post('reject-request/{id}', 'RequestController@rejectRequest');
    Route::post('delete-request', 'RequestController@deleteRequest');
    Route::post('delete-my-request', 'RequestController@deleteMyRequest');
    Route::post('request-brand', 'RequestController@requestBrand');

    //Product Controller
    Route::get('product', 'ProductController@index');
    Route::get('new-product-info', 'ProductController@newProductInfo');
    Route::get('edit-product-info/{id}', 'ProductController@editProductInfo');
    Route::get('product-child-category-list/{id}', 'ProductController@productChildCategoryList');
    Route::post('create-product', 'ProductController@createProduct');
    Route::post('upload-product-image/{slug}', 'ProductController@uploadProductImage');
    Route::post('update-product/{id}', 'ProductController@updateProduct');
    Route::post('update-product-image/{slug}', 'ProductController@updateProductImage');
    Route::post('status-product/{id}', 'ProductController@statusProduct');
    Route::post('delete-product/{id}', 'ProductController@deleteProduct');
    Route::post('approve-product/{id}', 'ProductController@approveProduct');
    Route::post('suspend-product/{id}', 'ProductController@suspendProduct');
    Route::get('product-all', 'ProductController@productAll');
    Route::get('product-pending', 'ProductController@productPending');
    Route::get('product-suspend', 'ProductController@productSuspend');
    Route::post('search-product', 'ProductController@searchProduct');
    Route::post('search-product-all', 'ProductController@searchProductAll');
    Route::post('search-product-pending', 'ProductController@searchProductPending');
    Route::post('search-product-suspend', 'ProductController@searchProductSuspend');
});

Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {
    Route::get('app', 'AppController@app');
});
