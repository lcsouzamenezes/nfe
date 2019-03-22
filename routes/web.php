<?php

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

$router->get('/check', [
    'as' => 'profile', 'uses' => 'ExampleController@check'
]);

$router->get('/listar-nfe', [
    'as' => 'profile', 'uses' => '\App\Nfe\Http\Controllers\GerarNota@index'
]);

$router->post('/gerar-nfe','\App\Nfe\Http\Controllers\GerarNota@store');

$router->get('/download-nfe', [
    'as' => 'profile', 'uses' => '\App\Nfe\Http\Controllers\Download@index'
]);

$router->get('/gerar-nfe', '\App\Nfe\Http\Controllers\GerarNota@create');
