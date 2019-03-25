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

$router->get('/gerar-nfe', [
    'as' => 'profile', 'uses' => '\App\Nfe\Http\Controllers\GerarNota@index'
]);

$router->get('/db', function () {
    $users = DB::collection('teste')->get();
    dd($users);

});

$router->get(
    '/download/{codigoAcesso}/{ambiente}',
    [
        'as' => 'download', 'uses' => '\App\Nfe\Http\Controllers\Download@get'
    ]
);

$router->get(
    '/consulta/{codigoAcesso}/{ambiente}',
    [
        'as' => 'consulta', 'uses' => '\App\Nfe\Http\Controllers\Consulta@get'
    ]
);

$router->get(
    '/distribuicao/{ambiente}',
    [
        'as' => 'distribuicao', 'uses' => '\App\Nfe\Http\Controllers\Distribuicao@get'
    ]
);
