<?php

namespace App\Nfe\Http\Controllers;

use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;

class Download
{
    public function index()
    {
        $certPath = storage_path('app') . env('APP_CERTS_PATH', true) . '/certificado.pfx';
        $pfx = file_get_contents($certPath);

        $CNPJPath = storage_path('app') . env('APP_CNPJ_PATH', true) . '/CNPJ01.json';
        $cnpj01 = file_get_contents($CNPJPath);

        try {
            $certificate = Certificate::readPfx($pfx, env('APP_CART_PASSWORD', true));
            $tools = new Tools($cnpj01, $certificate);

            $tools->model(55);
            $tools->setEnvironment(1);


            $chave = '';
            $response = $tools->sefazDownload($chave, 1);
            $stz = new Standardize($response);
            $std = $stz->toStd();
            dd($std);
            if ($std->cStat != 138) {
                echo "Documento não retornado. [$std->cStat] $std->xMotivo";
                die;
            }
            $zip = $std->loteDistDFeInt->docZip;
            $xml = gzdecode(base64_decode($zip));

            header('Content-type: text/xml; charset=UTF-8');
            echo $xml;

        } catch (\Exception $e) {
            echo str_replace("\n", "<br/>", $e->getMessage());
        }
    }
}
