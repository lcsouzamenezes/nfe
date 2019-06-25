<?php

namespace App\Nfe\Http\Controllers;

use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Complements;
use Illuminate\Http\Request;

/**
 * Class ConsultarRecibo
 * @todo refatorar ações do certificado
 */
class ConsultarRecibo
{
    public function index(Request $request, $recibo, $ambiente)
    {
        $certPath = storage_path('app') . env('APP_CERTS_PATH', true) . '/certificado.pfx';
        $pfx = file_get_contents($certPath);

        $CNPJPath = storage_path('app') . env('APP_CNPJ_PATH', true) . '/CNPJ01.json';
        $cnpj01 = file_get_contents($CNPJPath);

        try {
            $certificate = Certificate::readPfx($pfx, env('APP_CART_PASSWORD', true));
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        try {
            $tools = new Tools($cnpj01, $certificate);
            $tools->setEnvironment($ambiente);

            $xmlResp = $tools->sefazConsultaRecibo($recibo);
            //transforma o xml de retorno em um stdClass
            $st = new Standardize();
            $std = $st->toStd($xmlResp);

            return response()->json($std, 200);
        } catch (\Exception $e) {
            echo str_replace("\n", "<br/>", $e->getMessage());
        }
    }
}
