<?php

use Illuminate\Http\Request;
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
$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->get('/users', 'UsersController@index');
$router->get('/users/{user_id}', 'UsersController@show');
$router->post('/users', 'UsersController@store');
$router->post('/users/consumers', 'UsersController@storeConsumers');
$router->post('/users/sellers', 'UsersController@storeSellers');
$router->post('/transactions', 'TransactionsController@store');
$router->get('/transactions/{transaction_id}', 'TransactionsController@show');
