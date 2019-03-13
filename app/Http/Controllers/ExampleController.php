<?php

namespace App\Http\Controllers;

use NFePHP\NFe\Make;
use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;

class ExampleController extends Controller
{
    public function __construct()
    { }

    public function check()
    {
        $certPath = storage_path('app') . env('APP_CERTS_PATH', true) . '/certificado.pfx';
        $pfx = file_get_contents($certPath);

        $CNPJPath = storage_path('app') . env('APP_CNPJ_PATH', true) . '/CNPJ01.json';
        $cnpj01 = file_get_contents($CNPJPath);

        try {
            $certificate = Certificate::readPfx($pfx, env('APP_CART_PASSWORD', true));
            $tools = new Tools($cnpj01, $certificate);
            $tools->model('55');

            //guilherme
            $chave = '';

            $response = $tools->sefazConsultaChave($chave);
            $stdCl = new Standardize($response);
            //nesse caso $std irá conter uma representação em stdClass do XML
            $std = $stdCl->toStd();
            var_dump($std);
            die;

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
