<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Ajax\LocationController;
use App\Http\Controllers\Ajax\DashboardController as AjaxDashboardController;
use App\Http\Controllers\Ajax\AttributeController as AjaxAttributeController;
use App\Http\Controllers\Backend\GeneratorController;
use App\Http\Controllers\Backend\UserCatalogueController;
use App\Http\Controllers\Backend\LanguageController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\PostCatalogueController;
use App\Http\Controllers\Backend\PostController;
use App\Http\Controllers\Backend\ProductCatalogueController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\AttributeCatalogueController;
use App\Http\Controllers\Backend\AttributeController;
//@@new-controller@@


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Auth

Route::get('auth/login', [AuthController::class, 'index'])->middleware('login_middleware');
Route::post('auth/login', [AuthController::class, 'login']);
Route::get('auth/logout', [AuthController::class, 'logout']);

// User

Route::group(['middleware' => ['auth_middleware', 'locale'], 'prefix' => 'user'], function () {
    Route::get('{user}/edit', [UserController::class, 'edit'])->where(['user' => '[0-9]+']);
    Route::get('{user}/delete', [UserController::class, 'delete'])->where(['user' => '[0-9]+']);
});
Route::group(['middleware' => ['auth_middleware', 'locale']], function () {
    Route::resource('user', UserController::class)->except(['edit', 'delete']);
});

// UserCatalogue

Route::group(['middleware' => ['auth_middleware', 'locale'], 'prefix' => 'user-catalogue'], function () {
    Route::get('/{user_catalogue}/edit', [UserCatalogueController::class, 'edit'])->where(['user_catalogue' => '[0-9]+']);
    Route::get('/{user_catalogue}/delete', [UserCatalogueController::class, 'delete'])->where(['user_catalogue' => '[0-9]+']);
    Route::get('/permission', [UserCatalogueController::class, 'permission']);
    Route::post('/grant-permission', [UserCatalogueController::class, 'grantPermission']);
});
Route::group(['middleware' => ['auth_middleware', 'locale']], function () {
    Route::resource('user-catalogue', UserCatalogueController::class)->except(['edit', 'delete']);
});

// Language

Route::group(['middleware' => ['auth_middleware', 'locale'], 'prefix' => 'language'], function () {
    Route::get('/{language}/edit', [LanguageController::class, 'edit'])->where(['language' => '[0-9]+']);
    Route::get('/{language}/delete', [LanguageController::class, 'delete'])->where(['language' => '[0-9]+']);
    Route::get('/{language}/change', [LanguageController::class, 'changeLanguage'])->where(['language' => '[0-9]+']);
    Route::get('/{id}/{language_id}/{model_name}/translate', [LanguageController::class, 'translate'])->where(['id' => '[0-9]+', 'language_id' => '[0-9]+']);
    Route::post('/translate/{module_id}', [LanguageController::class, 'storeTranslation']);
});
Route::group(['middleware' => ['auth_middleware', 'locale']], function () {
    Route::resource('language', LanguageController::class)->except(['edit', 'delete']);
});

// PostCatalogue

Route::group(['middleware' => ['auth_middleware', 'locale'], 'prefix' => 'post-catalogue'], function () {
    Route::get('/{post_catalogue}/edit', [PostCatalogueController::class, 'edit'])->where(['post_catalogue' => '[0-9]+']);
    Route::get('/{post_catalogue}/delete', [PostCatalogueController::class, 'delete'])->where(['post_catalogue' => '[0-9]+']);
});
Route::group(['middleware' => ['auth_middleware', 'locale']], function () {
    Route::resource('post-catalogue', PostCatalogueController::class)->except(['edit', 'delete']);
});

//Post

Route::group(['middleware' => ['auth_middleware', 'locale'], 'prefix' => 'post'], function () {
    Route::get('/{post}/edit', [PostController::class, 'edit'])->where(['post' => '[0-9]+']);
    Route::get('/{post}/delete', [PostController::class, 'delete'])->where(['post' => '[0-9]+']);
});
Route::group(['middleware' => ['auth_middleware', 'locale']], function () {
    Route::resource('post', PostController::class)->except(['edit', 'delete']);
});

//Permission

Route::group(['middleware' => ['auth_middleware', 'locale'], 'prefix' => 'permission'], function () {
    Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->where(['permission' => '[0-9]+']);
    Route::get('/{permission}/delete', [PermissionController::class, 'delete'])->where(['permission' => '[0-9]+']);
});
Route::group(['middleware' => ['auth_middleware', 'locale']], function () {
    Route::resource('permission', PermissionController::class)->except(['edit', 'delete']);
});

//Generator

Route::group(['middleware' => ['auth_middleware', 'locale'], 'prefix' => 'generator'], function () {
    Route::get('/{generator}/edit', [GeneratorController::class, 'edit'])->where(['generator' => '[0-9]+']);
    Route::get('/{generator}/delete', [GeneratorController::class, 'delete'])->where(['generator' => '[0-9]+']);
});
Route::group(['middleware' => ['auth_middleware', 'locale']], function () {
    Route::resource('generator', GeneratorController::class)->except(['edit', 'delete']);
});

// Dashboard

Route::group(['middleware' => ['auth_middleware']], function () {
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('index', [DashboardController::class, 'index']);
        Route::get('blank', [DashboardController::class, 'blank']);
        Route::get('calendar', [DashboardController::class, 'calendar']);
        Route::get('forms', [DashboardController::class, 'forms']);
        Route::get('tables', [DashboardController::class, 'tables']);
        Route::get('tabs', [DashboardController::class, 'tabs']);
    });
});

//Product Catalogue

Route::group(['middleware' => ['auth_middleware', 'locale'], 'prefix' => 'product-catalogue'], function () {
    Route::get('/{product_catalogue}/edit', [ProductCatalogueController::class, 'edit'])->where(['product_catalogue' => '[0-9]+']);
    Route::get('/{product_catalogue}/delete', [ProductCatalogueController::class, 'delete'])->where(['product_catalogue' => '[0-9]+']);
});
Route::group(['middleware' => ['auth_middleware', 'locale']], function () {
    Route::resource('product-catalogue', ProductCatalogueController::class)->except(['edit', 'delete']);
});

//Product

Route::group(['middleware' => ['auth_middleware', 'locale'], 'prefix' => 'product'], function () {
    Route::get('/{product}/edit', [ProductController::class, 'edit'])->where(['product' => '[0-9]+']);
    Route::get('/{product}/delete', [ProductController::class, 'delete'])->where(['product' => '[0-9]+']);
});
Route::group(['middleware' => ['auth_middleware', 'locale']], function () {
    Route::resource('product', ProductController::class)->except(['edit', 'delete']);
});

//Attribute Catalogue

Route::group(['middleware' => ['auth_middleware', 'locale'], 'prefix' => 'attribute-catalogue'], function () {
    Route::get('/{attribute_catalogue}/edit', [AttributeCatalogueController::class, 'edit'])->where(['attribute_catalogue' => '[0-9]+']);
    Route::get('/{attribute_catalogue}/delete', [AttributeCatalogueController::class, 'delete'])->where(['attribute_catalogue' => '[0-9]+']);
});
Route::group(['middleware' => ['auth_middleware', 'locale']], function () {
    Route::resource('attribute-catalogue', AttributeCatalogueController::class)->except(['edit', 'delete']);
});

//Attribute

Route::group(['middleware' => ['auth_middleware', 'locale'], 'prefix' => 'attribute'], function () {
    Route::get('/{attribute}/edit', [AttributeController::class, 'edit'])->where(['attribute' => '[0-9]+']);
    Route::get('/{attribute}/delete', [AttributeController::class, 'delete'])->where(['attribute' => '[0-9]+']);
});
Route::group(['middleware' => ['auth_middleware', 'locale']], function () {
    Route::resource('attribute', AttributeController::class)->except(['edit', 'delete']);
});

//@@new-module@@

// AJAX

Route::get('ajax/location/getLocation', [LocationController::class, 'getLocation'])->middleware('auth_middleware')->middleware('locale');
Route::post('ajax/dashboard/setStatus', [AjaxDashboardController::class, 'setStatus'])->middleware('auth_middleware')->middleware('locale');
Route::post('ajax/dashboard/setStatusAll', [AjaxDashboardController::class, 'setStatusAll'])->middleware('auth_middleware')->middleware('locale');
Route::get('ajax/attribute/getAttribute', [AjaxAttributeController::class, 'getAttribute'])->middleware('auth_middleware')->middleware('locale');
Route::get('ajax/attribute/loadAttribute', [AjaxAttributeController::class, 'loadAttribute'])->middleware('auth_middleware')->middleware('locale');
