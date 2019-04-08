<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->get('/check', [
    'as' => 'profile', 'uses' => 'ExampleController@check'
]);

$router->post('/gerar-nfe/{ambiente}','\App\Nfe\Http\Controllers\GerarNota@index');

$router->get('/consultar-recibo/{recibo}/{ambiente}','\App\Nfe\Http\Controllers\ConsultarRecibo@index');

$router->get('/db', function () {
    $user = \App\NfeModel::all();
    dd($user);
});

$router->get(
    '/download/{codigoAcesso}/{ambiente}',
    [
        'as' => 'download', 'uses' => '\App\Nfe\Http\Controllers\Download@get'
    ]
);

$router->get(
    '/consultar/{codigoAcesso}/{ambiente}',
    [
        'as' => 'consulta', 'uses' => '\App\Nfe\Http\Controllers\Consultar@get'
    ]
);

$router->get(
    '/distribuicao/{ambiente}',
    [
        'as' => 'distribuicao', 'uses' => '\App\Nfe\Http\Controllers\Distribuicao@get'
    ]
);

$router->get(
    '/nota',
    [
        'as' => 'nota', 'uses' => '\App\Nfe\Http\Controllers\Nota@index'
    ]
);
