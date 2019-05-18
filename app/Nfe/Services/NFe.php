<?php

namespace app\Nfe\Services;

use App\Nfe\Models\Cert;
use \App\Nfe\Models\NFe as NFeModel;
use Illuminate\Support\Facades\Storage;
use NFePHP\NFe\Tools as Tools;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Complements;

class NFe
{
    protected $tools;

    public function config($certId)
    {
        $certModel = new Cert();
        $cert = $certModel->where('cnpj', $certId)->first();

        $certPath = env('APP_CERTS_PATH', true) . '/' . $cert->path;
        $pfx = Storage::disk('local')->get($certPath);

        $this->tools = new Tools($cert->toJson(), Certificate::readPfx($pfx, $cert->pass));
    }

    public function assinarNFe($xml)
    {
        $xmlAssinado = $this->tools->signNFe($xml);
        return $xmlAssinado;
    }

    public function salvarNFe($xml, $ambiente)
    {
        $salvarNFe = new NFeModel();
        $st = new Standardize();

        $idLote = str_pad(100, 15, '0', STR_PAD_LEFT); // Identificador do lote
        $resp = $this->tools->sefazEnviaLote([$xml], $idLote);
        $respostaSefazEnviaLote = $st->toArray($resp);

        $retorno = $this->tools->sefazConsultaRecibo($respostaSefazEnviaLote['infRec']['nRec'], $ambiente);
        $protocoledXML = Complements::toAuthorize($xml, $retorno);

        $data = $st->toArray($protocoledXML);

        $salvarNFe->data = $data;
        $salvarNFe->chNFe = $data['protNFe']['infProt']['chNFe'];
        $salvarNFe->tpAmb = $data['protNFe']['infProt']['tpAmb'];
        $salvarNFe->save();

        $disk = Storage::disk('s3');
        $disk->put($data['protNFe']['infProt']['chNFe'].'.xml', $protocoledXML);
    }
}
