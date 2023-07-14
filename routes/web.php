<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix' => 'api'], function () use ($router) {
    // Categories Routes
    $router->get('/categories', CategoryController::class . '@index');
    $router->get('/categories/{id}', CategoryController::class . '@show');
    $router->post('/categories', CategoryController::class . '@store');
    $router->put('/categories/{id}', CategoryController::class . '@update');
    $router->delete('/categories/{id}', CategoryController::class . '@destroy');

    // Customers Routes
    $router->get('/customers', CustomerController::class . '@index');
    $router->get('/customers/{id}', CustomerController::class . '@show');
    $router->post('/customers', CustomerController::class . '@store');
    $router->put('/customers/{id}', CustomerController::class . '@update');
    $router->delete('/customers/{id}', CustomerController::class . '@destroy');

     // Products Routes
     $router->get('/products', ProductController::class . '@index');
     $router->get('/products/{id}', ProductController::class . '@show');
     $router->post('/products', ProductController::class . '@store');
     $router->put('/products/{id}', ProductController::class . '@update');
     $router->delete('/products/{id}', ProductController::class . '@destroy');

     // Suppliers Routes
    $router->get('/suppliers', SupplierController::class . '@index');
    $router->get('/suppliers/{id}', SupplierController::class . '@show');
    $router->post('/suppliers', SupplierController::class . '@store');
    $router->put('/suppliers/{id}', SupplierController::class . '@update');
    $router->delete('/suppliers/{id}', SupplierController::class . '@destroy');

    // Transactions Routes
    $router->get('/transactions', TransactionController::class . '@index');
    $router->get('/transactions/{id}', TransactionController::class . '@show');
    $router->post('/transactions', TransactionController::class . '@store');
    $router->put('/transactions/{id}', TransactionController::class . '@update');
    $router->delete('/transactions/{id}', TransactionController::class . '@destroy');
});
