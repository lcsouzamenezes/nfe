<?php

namespace App\Nfe\Http\Controllers;

use App\Nfe\Services\NFe as NFeService;
use app\Nfe\Services\NFe;
use NFePHP\NFe\Make;
use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Complements;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Nfe\Models\NFe as NFeModel;
use NFePHP\Common\Exception\ValidatorException;

class CriarNotaFiscal
{

    public function index(
        Request $request,
        $ambiente,
        $certId
    ){
        $nfe = new Make();
        $std = new \stdClass();

        $service = new NFeService();
        $tools = $service->config($certId);

        $data = new \DateTime();
        $timeZone = $data->format(\DateTime::ATOM);
        $nNF = $request->input('nNF');

        $natOp = $request->input('natOp');

        $CNPJPath = storage_path('app') . env('APP_CNPJ_PATH', true) . '/CNPJ01.json';
        $dadosCnpj = file_get_contents($CNPJPath);
        $cnpj = json_decode($dadosCnpj);

        $naturezaOp = $request->input('natOp');

        //destinatario
        $nomeEmpresaDest = $request->input('xNome');
        $cepDest = $request->input('CEP');
        $logradouroDest = $request->input('xLgr');
        $complemento = $request->input('xCpl');
        $bairroDest = $request->input('xBairro');
        $munDest = $request->input('xMun');
        $nroDest = $request->input('nro');
        $ufDest = $request->input('UF');
        $cpfDest = $request->input('CPF');

        //produtos
        $nomeProduto = $request->input('xProd');
        $qtdComercial = $request->input('qCom');
        $qtdTributavel = $request->input('qTrib');
        $valorUnitarioComercial = $request->input('vUnCom');
        $valorUnitarioTributario = $request->input('vUnTrib');

        $std->versao = '4.00';
        $std->Id = null;
        $std->pk_nItem = '';
        $nfe->taginfNFe($std);

        $std = new \stdClass();
        $std->cUF = 53; //coloque um código real e válido
        $std->cNF = rand(11111111, 99999999);
        $std->natOp = $natOp;
        $std->mod = 55;
        $std->serie = 1;
        $std->nNF = $nNF;
        $std->dhEmi = $timeZone;
        $std->dhSaiEnt = $timeZone;
        $std->tpNF = 1;
        $std->idDest = 1; // Destino da operação1=Operação interna; 2=Operação interestadual; 3=Operação com exterior.
        $std->cMunFG = '5300108';
        $std->tpImp = 1;
        $std->tpEmis = 1;
        $std->cDV = 2;
        $std->tpAmb = $ambiente;
        $std->finNFe = 1;
        $std->indFinal = 0;
        $std->indPres = 0; // Presença do Comprador Indicador de presença do comprador no
        $std->procEmi = '0';
        $std->verProc = 1;
        $nfe->tagide($std);

        $std = new \stdClass();
        $std->xNome = $cnpj->razaosocial;
        $std->IE = $cnpj->IE;
        $std->CRT = 3;
        $std->CNPJ = $cnpj->cnpj;
        $nfe->tagemit($std);

        $std = new \stdClass();
        $std->xLgr = $cnpj->logradouro;
        $std->nro = '203';
        $std->xBairro = $cnpj->bairro;
        $std->cMun = $cnpj->ibge; //Código de município precisa ser válido e igual o  cMunFG
        $std->xMun = $cnpj->localidade;
        $std->UF = $cnpj->siglaUF;
        $std->CEP = $cnpj->cep;
        $std->cPais = $cnpj->codPais;
        $std->xPais = $cnpj->pais;
        $nfe->tagenderEmit($std);

        //destinatário
        $stdDestinatario = new \stdClass();
        $stdDestinatario->xNome = $nomeEmpresaDest;
        $stdDestinatario->indIEDest = 2; //2=Contribuinte isento de Inscrição no cadastro de Contribuintes do ICMS;
        $stdDestinatario->IE = '';
        $stdDestinatario->CPF = $cpfDest;
        $nfe->tagdest($stdDestinatario);

        $std = new \stdClass();
        $std->xLgr = $logradouroDest;
        $std->nro = $nroDest;
        $std->xBairro = $bairroDest;
        $std->cMun = 5300108;
        $std->xMun = $munDest;
        $std->xCpl = $complemento;
        $std->UF = $ufDest;
        $std->CEP = $cepDest;
        $std->cPais = $cnpj->codPais;
        $std->xPais = $cnpj->pais;
        $nfe->tagenderDest($std);

        $std = new \stdClass();
        $std->CNPJ = $cnpj->cnpjMinc; //indicar um CNPJ ou CPF
        $nfe->tagautXML($std);

        //produtos
        $stdProd = new \stdClass();
        $stdProd->item = 1;
        $stdProd->cEAN = 'SEM GTIN';
        $stdProd->cEANTrib = 'SEM GTIN';
        $stdProd->cProd = '0001';
        $stdProd->xProd = $nomeProduto;
        $stdProd->NCM = '84669330';
        $stdProd->CFOP = '5102';
        $stdProd->uCom = 'PÇ';
        $stdProd->qCom = $qtdComercial;
        $stdProd->qTrib = $qtdTributavel;
        $stdProd->uTrib = 'PÇ';
        $stdProd->vUnCom = $valorUnitarioComercial;
        $stdProd->vUnTrib = $valorUnitarioTributario;
        $stdProd->vProd = number_format(($stdProd->qTrib * $stdProd->vUnTrib), 2, '.', '');
        $stdProd->indTot = 1;
//        $stdProd->vFrete = number_format("12.88", 2, '.', '');
        $nfe->tagprod($stdProd);

        $std = new \stdClass();
        $std->item = 1;
        $std->vTotTrib = 10.99;
        $nfe->tagimposto($std);

        $stdICMS = new \stdClass();
        $stdICMS->item = 1;
        $stdICMS->orig = 0;
        $stdICMS->CST = '00';
        $stdICMS->modBC = 0;
        $stdICMS->vBC = number_format($stdProd->vProd, 2, '.', '');
        $stdICMS->pICMS = '12';
        $stdICMS->vICMS = number_format($stdICMS->vBC * ($stdICMS->pICMS / 100), 2);
        $nfe->tagICMS($stdICMS);

        $std = new \stdClass();
        $std->item = 1;
        $std->cEnq = '999';
        $std->CST = '50';
        $std->vIPI = 0;
        $std->vBC = 0;
        $std->pIPI = 0;
        $nfe->tagIPI($std);

        $std = new \stdClass();
        $std->item = 1;
        $std->CST = '07';
        $std->vBC = 0;
        $std->pPIS = 0;
        $std->vPIS = 0;
        $nfe->tagPIS($std);

        $std = new \stdClass();
        $std->item = 1;
        $std->CST = '07';
        $std->vBC = 0;
        $std->pCOFINS = 0;
        $std->vCOFINS = 0;
        $std->qBCProd = 0;
        $std->vAliqProd = 0;
        $nfe->tagCOFINS($std);

        $stdIMCStot = new \stdClass();
        $stdIMCStot->vBC = number_format($stdProd->vProd, 2, '.', '');
        $stdIMCStot->vICMS = $stdICMS->vICMS;
        $stdIMCStot->vICMSDeson = 0.00;
        $stdIMCStot->vBCST = 0.00;
        $stdIMCStot->vST = 0.00;
        $stdIMCStot->vProd = ($stdProd->qTrib * $stdProd->vUnTrib);
        $stdIMCStot->vFrete = $stdProd->vFrete;
        $stdIMCStot->vSeg = 0.00;
        $stdIMCStot->vDesc = 0.00;
        $stdIMCStot->vII = 0.00;
        $stdIMCStot->vIPI = 0.00;
        $stdIMCStot->vPIS = 0.00;
        $stdIMCStot->vCOFINS = 0.00;
        $stdIMCStot->vOutro = 0.00;
        $stdIMCStot->vNF = ($stdIMCStot->vProd - $stdIMCStot->vDesc -
            $stdIMCStot->vICMSDeson +
            $stdIMCStot->vST +
            $stdIMCStot->vFrete +
            $stdIMCStot->vSeg +
            $stdIMCStot->vOutro +
            $stdIMCStot->vII +
            $stdIMCStot->vIPI
        );
        $stdIMCStot->vTotTrib = 0.00;
        $nfe->tagICMSTot($stdIMCStot);

        $std = new \stdClass();
        $std->modFrete = 1;
        $nfe->tagtransp($std);

        $std = new \stdClass();
        $std->item = 1;
        $std->qVol = 2;
        $std->esp = 'caixa';
        $std->marca = 'OLX';
        $std->nVol = '11111';
        $std->pesoL = 10.00;
        $std->pesoB = 11.00;
        $nfe->tagvol($std);

        $std = new \stdClass();
        $std->nFat = '002';
        $std->vOrig = 100;
        $std->vLiq = 100;
        $nfe->tagfat($std);

        $std = new \stdClass();
        $std->nDup = '001';
        $std->dVenc = date('Y-m-d');
        $std->vDup = 100;
        $nfe->tagdup($std);

        $std = new \stdClass();
        $std->vTroco = 0;
        $nfe->tagpag($std);

        $std = new \stdClass();
        $std->indPag = 0;
        $std->tPag = "01"; //Forma de pagamento
        $std->vPag = number_format($stdProd->vProd, 2, '.', '');
        $std->indPag = 0;
        $nfe->tagdetPag($std);

        $xml = $nfe->getXML(); // O conteúdo do XML fica armazenado na variável $xml

        $service->assinarNFe($xml);

//        $certPath = storage_path('app') . env('APP_CERTS_PATH', true) . '/certificado.pfx';
//        $pfx = file_get_contents($certPath);
//
//        $certificate = Certificate::readPfx($pfx, env('APP_CART_PASSWORD', true));
//        try {
//
//            $tools = new Tools($dadosCnpj, $certificate);
//            $xmlAssinado = $tools->signNFe($xml);
//
//            $idLote = str_pad(100, 15, '0', STR_PAD_LEFT); // Identificador do lote
//            $resp = $tools->sefazEnviaLote([$xmlAssinado], $idLote);
//
//            $st = new Standardize();
//            $resp = $st->toArray($resp);
//
//            $retorno = $tools->sefazConsultaRecibo($resp['infRec']['nRec'], $ambiente);
//            $protocoledXML = Complements::toAuthorize($xmlAssinado, $retorno);
//
//            $st = new Standardize();
//            $data = $st->toArray($protocoledXML);
//
//            $nfe = new NFeModel();
//            $nfe->data = $data;
//            $nfe->chNFe = $data['protNFe']['infProt']['chNFe'];
//            $nfe->tpAmb = $data['protNFe']['infProt']['tpAmb'];
//            $nfe->save();
//
//            $diskLocal = $fs->disk('s3');
//            $diskLocal->put($data['protNFe']['infProt']['chNFe'].'.xml', $protocoledXML);
//
//            return response()->json($resp, 200);
//        } catch (ValidatorException $e) {
//            return response()->json($e->getMessage(), 400);
//        } catch (\Exception $e) {
//            return response()->json($e->getMessage(), 400);
//        }
//    }
}
}
