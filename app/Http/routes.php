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

use App\Orders\Order;
use Omnipay\Omnipay;

Route::get('/', 'PagesController@home');
Route::get('thanks', 'PagesController@thanks');
Route::get('collections', 'PagesController@collections');
Route::get('collections/{slug}', 'PagesController@categories');
Route::get('categories/{slug}', 'PagesController@products');
Route::get('product/{slug}', 'PagesController@product');
Route::get('cart', 'PagesController@showCart');
Route::get('checkout', 'CheckoutController@showCheckout');
Route::post('checkout', 'CheckoutController@doCheckout');

Route::get('blog', 'BlogController@index');
Route::get('blog/{slug}', 'BlogController@show');

//Route::get('cancel_paypal', function() {
//    dd(\Illuminate\Support\Facades\Input::all());
//});

//Route::get('process_paypal/{orderNumber}', function($orderNumber, \App\Billing\PaypalTeller $paypalTeller) {
//    return $paypalTeller->completePurchase($orderNumber);
//});

Route::get('process_paypal/{orderNumber}', 'PaypalController@processPaypalPayment');
Route::get('cancel_paypal', 'PaypalController@handleCanceledPayment');

Route::get('api/products/{id}/options', 'Admin\ProductOptionsController@getOptionsAndValuesForProduct');

Route::get('api/cart', 'CartController@getCartItems');
Route::get('api/cart/summary', 'CartController@getCartSummary');
Route::get('api/cart/empty', 'CartController@emptyCart');
Route::get('api/cart/shipping', 'CartController@shippingPricesForWeight');
Route::post('api/cart', 'CartController@addItem');
Route::post('api/cart/{rowid}', 'CartController@updateItemQuantity');
Route::delete('api/cart/{rowid}', 'CartController@deleteItem');

Route::get('admin/uploads/galleries/{galleryId}/images', 'Admin\GalleriesController@index');

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function() {

    Route::group(['middleware' => 'auth'], function() {
        Route::get('/', 'PagesController@dashboard');
        Route::get('logout', 'AuthController@doLogout');
        Route::get('register', 'UsersController@showRegister');
        Route::get('users', 'UsersController@index');
        Route::post('users/register', 'UsersController@postRegistration');
        Route::get('users/{id}/edit', 'UsersController@edit');
        Route::post('users/{id}/edit', 'UsersController@update');
        Route::delete('users/{id}', 'UsersController@delete');
        Route::get('users/password/reset', 'UsersController@showPasswordReset');
        Route::post('users/password/reset', 'UsersController@resetPassword');

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

        Route::get('products/index', 'ProductsSearchController@showSearchPage');
        Route::get('products/{productId}', 'ProductsController@show');
        Route::get('categories/{categoryId}/products/create', 'ProductsController@create');
        Route::post('categories/{categoryId}/products', 'ProductsController@store');
        Route::get('products/{productId}/edit', 'ProductsController@edit');
        Route::post('products/{productId}', 'ProductsController@update');
        Route::delete('products/{productId}', 'ProductsController@delete');
        Route::post('api/products/{productId}/availability', 'ProductsController@setAvailability');

        Route::post('products/{productId}/tags', 'ProductTagsController@syncTags');
        Route::get('products/{productId}/tags', 'ProductTagsController@getProductTags');

        Route::get('tags', 'TagsController@index');

        Route::get('products/{productId}/options', 'ProductOptionsController@index');
        Route::post('products/{productId}/options', 'ProductOptionsController@store');
        Route::delete('productoptions/{optionId}', 'ProductOptionsController@delete');

        Route::get('productoptions/{optionId}/values', 'ProductOptionValuesController@index');
        Route::post('productoptions/{optionId}/values', 'ProductOptionValuesController@store');
        Route::delete('optionvalues/{valueId}', 'ProductOptionValuesController@delete');

        Route::get('products/{productId}/customisations', 'CustomisationsController@index');
        Route::post('products/{productId}/customisations', 'CustomisationsController@store');
        Route::delete('customisations/{customisationId}', 'CustomisationsController@delete');

        Route::get('api/products/search/{term}', 'ProductsSearchController@search');

        Route::post('uploads/collections/{collectionId}/cover', 'CollectionsController@storeCoverPic');
        Route::post('uploads/categories/{categoryId}/cover', 'CategoriesController@storeCoverPic');
        Route::post('uploads/products/{productId}/cover', 'ProductsController@storeCoverPic');

        Route::post('uploads/products/{productId}/gallery', 'ProductsController@storeGalleryImage');


        Route::post('uploads/galleries/{galleryId}/images', 'GalleriesController@storeImage');
        Route::delete('uploads/galleries/{galleryId}/images/{imageId}', 'GalleriesController@deleteImage');

        Route::get('shipping/locations', 'ShippingRulesController@getLocations');
        Route::post('shipping/locations', 'ShippingRulesController@addRuleLocation');
        Route::post('shipping/locations/{id}', 'ShippingRulesController@updateRuleLocation');
        Route::delete('shipping/locations/{id}', 'ShippingRulesController@deleteRuleLocation');
        Route::get('shipping/locations/{locationId}/getfreeprice', 'ShippingRulesController@getFreeShippingPrice');
        Route::post('shipping/locations/{locationId}/setfreeprice', 'ShippingRulesController@setFreeShippingPrice');
        Route::delete('shipping/locations/{locationId}/removefreeprice', 'ShippingRulesController@removeFreeShippingPrice');

        Route::get('shipping/locations/{locationId}/weightclasses', 'ShippingRulesController@getLocationWeightClasses');
        Route::post('shipping/locations/{locationId}/weightclasses', 'ShippingRulesController@createWeightClass');
        Route::post('shipping/weightclasses/{classId}', 'ShippingRulesController@updateWeightClass');
        Route::delete('shipping/weightclasses/{classId}', 'ShippingRulesController@deleteWeightClass');
        Route::get('shipping', 'ShippingRulesController@show');

        Route::get('orders/{status?}', 'OrdersController@index');
        Route::get('orders/show/{orderId}', 'OrdersController@show');

        Route::post('api/orders/{orderId}/fulfill', 'OrdersController@setFulfilledStatus');
        Route::post('api/orders/{orderId}/cancel', 'OrdersController@setCancelledStatus');

        Route::get('site-content/pages/{pageId}', 'EdiblesController@showPage');
        Route::get('site-content/pages/{pageId}/textblocks/{textblockId}/edit', 'EdiblesController@editTextblock');
        Route::get('site-content/pages/{pageId}/galleries/{galleryId}/edit', 'EdiblesController@editGallery');
        Route::post('site-content/textblocks/{textblockId}', 'EdiblesController@updateTextblock');
        Route::post('site-content/galleries/{galleryId}/uploads', 'EdiblesController@storeUploadedImage');
        Route::get('site-content/galleries/{galleryId}/uploads', 'EdiblesController@getGalleryImages');

        Route::get('blog/posts', 'BlogController@index');
        Route::post('blog/posts', 'BlogController@store');
        Route::get('blog/posts/{postId}/edit', 'BlogController@edit');
        Route::post('blog/posts/{postId}', 'BlogController@update');
        Route::post('blog/posts/{postId}/images/uploads', 'BlogController@storeImageUpload');
        Route::post('blog/posts/{postId}/publish', 'BlogController@setPublishedState');
    });

    Route::group(['middleware' => 'guest'], function() {
        Route::get('login', 'AuthController@showLogin');
        Route::post('login', 'AuthController@doLogin');


    });

});


