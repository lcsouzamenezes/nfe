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
    '/nfe/distribuicao/{ambiente}/{certId}',
    [
        'as' => 'distribuicao', 'uses' => '\App\Nfe\Http\Controllers\Distribuicao@get'
    ]
);

$router->get(
    '/buscar-notas/{id}',
    [
        'as' => 'notas', 'uses' => '\App\Nfe\Http\Controllers\BuscarDandosNFE@buscarDadosNfe'
    ]
);

$router->get(
    '/listar-notas/{usuario}',
    [
        'as' => 'notas', 'uses' => '\App\Nfe\Http\Controllers\ListarNotas@listarNotas'
    ]
);

$router->group(['middleware' => 'jwt'], function () use ($router) {
    $router->get(
        '/user',
        [
            'uses' => 'ExampleController@check'
        ]
    );
});

$router->get(
    '/conta/usuario/{token}',
    [
        'as' => 'usuario', 'uses' => '\App\Conta\Http\Controllers\Autenticacao@usuario'
    ]
);

$router->post('/nfe/criar/{ambiente}/{certId}','\App\Nfe\Http\Controllers\CriarNFe@index');

$router->get('/nfe/download-local/{chNfe}','\App\Nfe\Http\Controllers\DownloadXml@get');