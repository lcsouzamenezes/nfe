<?php

namespace App\Nfe\Http\Controllers;

use NFePHP\NFe\Make;
use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Complements;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Nfe\Http\Controllers\GerarNota;

class ConsultaRecibo
{
    public function index(Request $request, $nRec)
    {
        $certPath = storage_path('app') . env('APP_CERTS_PATH', true) . '/certificado.pfx';
        $pfx = file_get_contents($certPath);

        $CNPJPath = storage_path('app') . env('APP_CNPJ_PATH', true) . '/CNPJ01.json';
        $cnpj01 = file_get_contents($CNPJPath);
        $NFEPath = storage_path('app') . env('APP_NFE_PATH', true);

        try {
            $certificate = Certificate::readPfx($pfx, env('APP_CART_PASSWORD', true));
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        try {
            $tools = new Tools($cnpj01, $certificate);

            $xmlResp = $tools->sefazConsultaRecibo($nRec);
            //transforma o xml de retorno em um stdClass
            $st = new Standardize();
            $std = $st->toStd($xmlResp);

            if($std->cStat=='103') { //lote enviado
                //Lote ainda não foi precessado pela SEFAZ;
            }
            if($std->cStat=='105') { //lote em processamento
                //tente novamente mais tarde
            }

            if($std->cStat=='104'){ //lote processado (tudo ok)
                if($std->protNFe->infProt->cStat=='100'){ //Autorizado o uso da NF-e
                    $return = ["situacao"=>"autorizada",
                        "numeroProtocolo"=>$std->protNFe->infProt->nProt,
                        "xmlProtocolo"=> $xmlResp];

                    file_put_contents($NFEPath.'/'.$nRec.'.xml',$xmlResp);
//                    Complements::toAuthorize($xmlAssinado, $xmlResp);
//                    header('Content-type: text/xml; charset=UTF-8');
//                    echo $xml;

                }elseif(in_array($std->protNFe->infProt->cStat,["302"])){ //DENEGADAS
                    return $return = ["situacao"=>"denegada",
                        "numeroProtocolo"=>$std->protNFe->infProt->nProt,
                        "motivo"=>$std->protNFe->infProt->xMotivo,
                        "cstat"=>$std->protNFe->infProt->cStat,
                        "xmlProtocolo"=>$xmlResp];
                }else{ //não autorizada (rejeição)
                    return $return = ["situacao"=>"rejeitada",
                        "motivo"=>$std->protNFe->infProt->xMotivo,
                        "cstat"=>$std->protNFe->infProt->cStat];
                }
            } else { //outros erros possíveis
                return $return = ["situacao"=>"rejeitada",
                    "motivo"=>$std->xMotivo,
                    "cstat"=>$std->cStat];
            }

        } catch (\Exception $e) {
            echo str_replace("\n", "<br/>", $e->getMessage());
        }
    }
}
