<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->get('/check', [
    'as' => 'profile', 'uses' => 'ExampleController@check'
]);

$router->post('/gerar-nfe/{ambiente}','\App\Nfe\Http\Controllers\GerarNota@index');

$router->get('/consultar-nfe/{nRec}','\App\Nfe\Http\Controllers\ConsultarNota@index');

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
