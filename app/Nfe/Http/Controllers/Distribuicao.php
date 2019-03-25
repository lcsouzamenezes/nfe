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

class Distribuicao
{
    public function get(Request $request, $ambiente)
    {
        set_time_limit ( 600 );

        $certPath = storage_path('app') . env('APP_CERTS_PATH', true) . '/certificado.pfx';
        $pfx = file_get_contents($certPath);

        $CNPJPath = storage_path('app') . env('APP_CNPJ_PATH', true) . '/CNPJ01.json';
        $cnpj01 = file_get_contents($CNPJPath);

        $tools = new Tools($cnpj01, Certificate::readPfx($pfx, env('APP_CART_PASSWORD', true)));

        $tools->model('55');
        $tools->setEnvironment($ambiente);

        ////este numero deverá vir do banco de dados nas proximas buscas para reduzir
        ////a quantidade de documentos, e para não baixar várias vezes as mesmas coisas.
        $ultNSU = 0;
        $maxNSU = $ultNSU;
        $loopLimit = 50;
        $iCount = 0;

        //executa a busca de DFe em loop
        while ($ultNSU <= $maxNSU) {

            $iCount++;
            if ($iCount >= $loopLimit) {
                break;
            }
            try {
                //executa a busca pelos documentos
                $resp = $tools->sefazDistDFe($ultNSU);
                dd($resp);
            } catch (\Exception $e) {
                echo $e->getMessage();
                //tratar o erro
            }

            //extrair e salvar os retornos
            $dom = new \DOMDocument();
            $dom->loadXML($resp);
            $node = $dom->getElementsByTagName('retDistDFeInt')->item(0);
            $tpAmb = $node->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            $verAplic = $node->getElementsByTagName('verAplic')->item(0)->nodeValue;
            $cStat = $node->getElementsByTagName('cStat')->item(0)->nodeValue;
            $xMotivo = $node->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            $dhResp = $node->getElementsByTagName('dhResp')->item(0)->nodeValue;
            $ultNSU = $node->getElementsByTagName('ultNSU')->item(0)->nodeValue;
            $maxNSU = $node->getElementsByTagName('maxNSU')->item(0)->nodeValue;
            $lote = $node->getElementsByTagName('loteDistDFeInt')->item(0);
            if (empty($lote)) {
                //lote vazio
                continue;
            }
            //essas tags irão conter os documentos zipados
            $docs = $lote->getElementsByTagName('docZip');
            foreach ($docs as $doc) {
                $numnsu = $doc->getAttribute('NSU');
                $schema = $doc->getAttribute('schema');
                //descompacta o documento e recupera o XML original
                $content = gzdecode(base64_decode($doc->nodeValue));
                //identifica o tipo de documento
                $tipo = substr($schema, 0, 6);
                //processar o conteudo do NSU, da forma que melhor lhe interessar
                //esse processamento depende do seu aplicativo
            }
            sleep(2);
        }
    }
}
