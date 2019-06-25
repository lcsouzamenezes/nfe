<?php

namespace App\Nfe\Http\Controllers;

use NFePHP\NFe\Make;
use NFePHP\NFe\Tools as Tools;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Complements;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use \App\Nfe\Exceptions\NfeException;

/**
 * Class Consultar
 * @todo refatoração
 */
class Consultar
{
    public function get(Request $request, $codigoAcesso, $ambiente)
    {
        $certPath = storage_path('app') . env('APP_CERTS_PATH', true) . '/certificado.pfx';
        $pfx = file_get_contents($certPath);

        $CNPJPath = storage_path('app') . env('APP_CNPJ_PATH', true) . '/CNPJ01.json';
        $cnpj01 = file_get_contents($CNPJPath);

        try {
            $tools = new Tools($cnpj01, Certificate::readPfx($pfx, env('APP_CART_PASSWORD', true)));
            $tools->model('55');
            $tools->setEnvironment($ambiente);

            $response = $tools->sefazConsultaChave($codigoAcesso);

            $stdCl = new Standardize($response);
            $std = $stdCl->toStd();

            return response()->json($std, 200);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
