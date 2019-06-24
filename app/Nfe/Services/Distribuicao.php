<?php

namespace app\Nfe\Services;

use App\Nfe\Models\Cert;
use \App\Nfe\Models\Distribuicao as DistribuicaoModel;
use \App\Nfe\Models\NFe as NFeModel;

use Illuminate\Support\Facades\Storage;

use NFePHP\NFe\Make;
use NFePHP\NFe\Tools as Tools;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Complements;
use Carbon\Carbon;

class Distribuicao
{

    public function config($certId)
    {
        $certModel = new Cert();
        $cert = $certModel->where('cnpj', $certId)->first();

        $certPath = env('APP_CERTS_PATH', true) . '/' . $cert->path;
        $pfx = Storage::disk('local')->get($certPath);

        return new Tools($cert->toJson(), Certificate::readPfx($pfx, $cert->pass));
    }

    public function salvarConsultaDistribuicao($xml)
    {
            $consulta = new DistribuicaoModel();
            $dom = new \DOMDocument();

            $dom->loadXML($xml);
            $node = $dom->getElementsByTagName('retDistDFeInt')->item(0);

            $tpAmb = $node->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            $verAplic = $node->getElementsByTagName('verAplic')->item(0)->nodeValue;
            $cStat = $node->getElementsByTagName('cStat')->item(0)->nodeValue;
            $xMotivo = $node->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            $dhResp = Carbon::create($node->getElementsByTagName('dhResp')->item(0)->nodeValue);
            $ultNSU = $node->getElementsByTagName('ultNSU')->item(0)->nodeValue;
            $maxNSU = $node->getElementsByTagName('maxNSU')->item(0)->nodeValue;

            $consulta->tpAmb = $tpAmb;
            $consulta->verAplic = $verAplic;
            $consulta->cStat = $cStat;
            $consulta->xMotivo = $xMotivo;
            $consulta->dhResp = $dhResp;
            $consulta->ultNSU = $ultNSU;
            $consulta->maxNSU = $maxNSU;
            $consulta->save();
    }

    public function salvarNFe($docs)
    {
        foreach ($docs as $doc) {
            $numnsu = $doc->getAttribute('NSU');
            $schema = $doc->getAttribute('schema');
            //descompacta o documento e recupera o XML original
            $content = gzdecode(base64_decode($doc->nodeValue));
            //identifica o tipo de documento
            $tipo = substr($schema, 0, 6);

            $nfe = new NFeModel();
            $stdCl = new Standardize($content);
            $data = $stdCl->toArray();

            $nfe->data = $data;
            $nfe->chNFe = $data['protNFe']['infProt']['chNFe'];
            $nfe->tpAmb = $data['protNFe']['infProt']['tpAmb'];
            $nfe->save();

            $disk = Storage::disk('s3');
            $disk->put($data['protNFe']['infProt']['chNFe'].'.xml', $content);
        }
    }
}
