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

class Download
{
    /** @TODO
     *
     * Não foi possivel verificar um caso de sucesso por não termos acessos a nostas como interessado
     */
    public function get(Request $request, $codigoAcesso, $ambiente)
    {
        $code = 200;

        $certPath = storage_path('app') . env('APP_CERTS_PATH', true) . '/certificado.pfx';
        $pfx = file_get_contents($certPath);

        $CNPJPath = storage_path('app') . env('APP_CNPJ_PATH', true) . '/CNPJ01.json';
        $cnpj01 = file_get_contents($CNPJPath);

        $tools = new Tools($cnpj01, Certificate::readPfx($pfx, env('APP_CART_PASSWORD', true)));
        $tools->model('55');
        $tools->setEnvironment($ambiente);

        try {
            $response = $tools->sefazDownload($codigoAcesso);
            $stz = new Standardize($response);
            $std = $stz->toStd();

            if ($std->cStat !== 100) {
                throw new NfeException(null, null, null, $std);
            }

            return response()->json($e->getObj(), 200);
        } catch (NfeException $e) {
            return response()->json($e->getObj(), 400);
        }
    }
}
