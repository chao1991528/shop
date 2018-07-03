<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('users', UserController::class);
    $router->resource('products', ProductController::class);
    $router->get('/orders', 'OrdersController@index')->name('admin.orders.index');
    $router->get('/orders/{order}', 'OrdersController@show')->name('admin.orders.show');
    $router->post('/ship/{order}', 'OrdersController@ship')->name('admin.orders.ship');
});
