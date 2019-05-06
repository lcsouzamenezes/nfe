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
use \App\ConsultaDistribuicao;
use \App\NfeModel;

use Carbon\Carbon;

class Distribuicao
{
    public function get(
        Request $request,
        $ambiente,
        \Illuminate\Contracts\Filesystem\Factory $fs
    ) {
        $ultimaConsulta = ConsultaDistribuicao::orderBy('dhResp', 'desc')->first();

        /* dd($ultimaConsulta->getDhResp()); */

        set_time_limit(600);

        $certPath = storage_path('app') . env('APP_CERTS_PATH', true) . '/minc.pfx';
        $pfx = file_get_contents($certPath);

        $CNPJPath = storage_path('app') . env('APP_CNPJ_PATH', true) . '/CNPJ02.json';
        $cnpj01 = file_get_contents($CNPJPath);

        $tools = new Tools($cnpj01, Certificate::readPfx($pfx, 'minc2018'));

        $tools->model('55');
        $tools->setEnvironment($ambiente);

        ////este numero deverá vir do banco de dados nas proximas buscas para reduzir
        ////a quantidade de documentos, e para não baixar várias vezes as mesmas coisas.
        $ultNSU = 6;
        $maxNSU = $ultNSU;
        $loopLimit = 2;
        $iCount = 0;

        //executa a busca de DFe em loop
        while ($ultNSU <= $maxNSU) {
            $iCount++;
            if ($iCount >= $loopLimit) {
                break;
            }
            try {
                //executa a busca pelos documentos
                $r = $tools->sefazDistDFe($ultNSU);
            } catch (\Exception $e) {
                echo $e->getMessage();
                //tratar o erro
            }

            //extrair e salvar os retornos
            $dom = new \DOMDocument();
            $dom->loadXML($r);
            $node = $dom->getElementsByTagName('retDistDFeInt')->item(0);

            $tpAmb = $node->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            $verAplic = $node->getElementsByTagName('verAplic')->item(0)->nodeValue;
            $cStat = $node->getElementsByTagName('cStat')->item(0)->nodeValue;
            $xMotivo = $node->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            $dhResp = Carbon::create($node->getElementsByTagName('dhResp')->item(0)->nodeValue);
            $ultNSU = $node->getElementsByTagName('ultNSU')->item(0)->nodeValue;
            $maxNSU = $node->getElementsByTagName('maxNSU')->item(0)->nodeValue;

            $consulta = new ConsultaDistribuicao();

            $consulta->tpAmb = $tpAmb;
            $consulta->verAplic = $verAplic;
            $consulta->cStat = $cStat;
            $consulta->xMotivo = $xMotivo;
            $consulta->dhResp = $dhResp;
            $consulta->ultNSU = $ultNSU;
            $consulta->maxNSU = $maxNSU;
            $consulta->save();

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

                $nfe = new NfeModel();
                $stdCl = new Standardize($content);
                $data = $stdCl->toArray();
                /* dd($data); */

                $nfe->infNFe = $data;
                $nfe->save();
                //processar o conteudo do NSU, da forma que melhor lhe interessar
                //esse processamento depende do seu aplicativo
                //
                $diskLocal = $fs->disk('s3');
                $diskLocal->put($data['NFe']['infNFe']['attributes']['Id'].'.xml', $content);
            }
            sleep(2);
        }
        dd('finalizado');
    }
}
