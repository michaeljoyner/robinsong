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

Route::get('/', 'PagesController@home');


Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function() {

    Route::group(['middleware' => 'auth'], function() {
        Route::get('/', 'PagesController@dashboard');
        Route::get('logout', 'AuthController@doLogout');
        Route::get('register', 'UsersController@showRegister');
        Route::post('users/register', 'UsersController@postRegistration');
        Route::get('users/{id}/edit', 'UsersController@edit');
        Route::post('users/{id}/edit', 'UsersController@update');
        Route::delete('users/{id}', 'UsersController@delete');

        Route::get('collections', 'CollectionsController@index');
        Route::get('collections/create', 'CollectionsController@create');
        Route::get('collections/{collectionId}', 'CollectionsController@show');
        Route::get('collections/{collectionId}/edit', 'CollectionsController@edit');
        Route::post('collections/{collectionId}', 'CollectionsController@update');
        Route::delete('collections/{collectionId}', 'CollectionsController@delete');
        Route::post('collections', 'CollectionsController@store');

        Route::get('categories/{categoryId}', 'CategoriesController@show');
        Route::get('collections/{collectionId}/categories/create', 'CategoriesController@create');
        Route::post('collections/{collectionId}/categories', 'CategoriesController@store');
        Route::get('categories/{categoryId}/edit', 'CategoriesController@edit');
        Route::post('categories/{categoryId}', 'CategoriesController@update');
        Route::delete('categories/{categoryId}', 'CategoriesController@delete');

        Route::get('categories/{categoryId}/products/create', 'ProductsController@create');
        Route::post('categories/{categoryId}/products', 'ProductsController@store');
        Route::get('products/{productId}/edit', 'ProductsController@edit');
        Route::post('products/{productId}', 'ProductsController@update');
        Route::delete('products/{productId}', 'ProductsController@delete');

        Route::post('uploads/collections/{collectionId}/cover', 'CollectionsController@storeCoverPic');
        Route::post('uploads/categories/{categoryId}/cover', 'CategoriesController@storeCoverPic');
        Route::post('uploads/products/{productId}/cover', 'ProductsController@storeCoverPic');

        Route::post('uploads/products/{productId}/gallery', 'ProductsController@storeGalleryImage');

    });

    Route::group(['middleware' => 'guest'], function() {
        Route::get('login', 'AuthController@showLogin');
        Route::post('login', 'AuthController@doLogin');
    });

});


