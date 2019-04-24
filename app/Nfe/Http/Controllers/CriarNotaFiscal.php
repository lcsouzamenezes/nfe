<?php

namespace App\Nfe\Http\Controllers;

use NFePHP\NFe\Make;
use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Complements;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\NfeModel;

class CriarNotaFiscal
{

    public function index(Request $request, $ambiente)
    {
        $nfe = new Make();
        $std = new \stdClass();

        $data = new \DateTime();
        $timeZone = $data->format(\DateTime::ATOM);
        $nNF = $request->input('nNF');

        $natOp = $request->input('natOp');

        $CNPJPath = storage_path('app') . env('APP_CNPJ_PATH', true) . '/CNPJ01.json';
        $dadosCnpj = file_get_contents($CNPJPath);
        $cnpj = json_decode($dadosCnpj);

        $naturezaOp = $request->input('natOp');

        //destinatario
        $nomeEmpresaDest  = $request->input('xNome');
        $logradouroDest = $request->input('xLgr');
        $nroDest =  $request->input('nro');
        $bairroDest =  $request->input('xBairro');
        $munDest =  $request->input('xMun');
        $cepDest =  $request->input('CEP');
        $ufDest =  $request->input('UF');

        //produtos
        $vProd = $request->input('vProd');
        $nomeProduto = $request->input('xProd');

        $std->versao = '4.00';
        $std->Id = null;
        $std->pk_nItem = '';
        $nfe->taginfNFe($std);

        $std = new \stdClass();
        $std->cUF = 53; //coloque um código real e válido
        $std->cNF = rand(11111111, 99999999);
        $std->natOp = $natOp; //Informar a natureza daoperação de que decorrer a saída ou a entrada, tais como venda, compra, transferência,
        $std->mod = 55;
        $std->serie = 1;
        $std->nNF = $nNF;
        $std->dhEmi = $timeZone;
        $std->dhSaiEnt = $timeZone;
        $std->tpNF = 1;
        $std->idDest = 1; //tipo da operação 1=Operação interna; 2=Operação interestadual; 3=Operação com exterior.
        $std->cMunFG = 5300108;
        $std->tpImp = 1;
        $std->tpEmis = 1;
        $std->cDV = 2;
        $std->tpAmb = $ambiente;
        $std->finNFe = 1;
        $std->indFinal = 0;
        $std->indPres = 0; //Indicador de presença do comprador no
        $std->procEmi = '0';
        $std->verProc = 1;
        $nfe->tagide($std);

        $std = new \stdClass();
        $std->xNome =  $cnpj->razaosocial;
        $std->IE = $cnpj->IE;
        $std->CRT = 3;
        $std->CNPJ = $cnpj->cnpj;
        $nfe->tagemit($std);

        $std = new \stdClass();
        $std->xLgr = $cnpj->bairro;
        $std->nro = '';
        $std->xBairro = $cnpj->bairro;
        $std->cMun = $cnpj->ibge; //Código de município precisa ser válido e igual o  cMunFG
        $std->xMun = $cnpj->localidade;
        $std->UF = $cnpj->siglaUF;
        $std->CEP = $cnpj->cep;
        $std->cPais = '1058';
        $std->xPais = $cnpj->pais;
        $nfe->tagenderEmit($std);

        //destinatário
        $stdDestinatario = new \stdClass();
        $stdDestinatario->xNome = $nomeEmpresaDest;
        $stdDestinatario->indIEDest = 2; //2=Contribuinte isento de Inscrição no cadastro de Contribuintes do ICMS;
        $stdDestinatario->IE = '';
        $stdDestinatario->CPF = '02014705477';
        $nfe->tagdest($stdDestinatario);

        $std = new \stdClass();
        $std->xLgr = $logradouroDest;
        $std->nro = $nroDest ;
        $std->xBairro = $bairroDest;
        $std->xMun = $munDest;
        $std->UF = $ufDest;
        $std->CEP = $cepDest;
        $std->cPais = '1058';
        $std->xPais = 'BRASIL';
        $nfe->tagenderDest($std);

        //produtos
        $std = new \stdClass();
        $std->item = 1;
        $std->cEAN = 'SEM GTIN';
        $std->cEANTrib = 'SEM GTIN';
        $std->cProd = '0001';
        $std->xProd = $nomeProduto;
        $std->NCM = '84669330';
        $std->CFOP = '5102';
        $std->uCom = 'PÇ';
        $std->qCom = '1.0000';
        $std->vUnCom = '10.99';
        $std->vProd = $vProd;
        $std->uTrib = 'PÇ';
        $std->qTrib = '1.0000';
        $std->vUnTrib = '10.99';
        $std->indTot = 1;
        $nfe->tagprod($std);

        $std = new \stdClass();
        $std->item = 1;
        $std->vTotTrib = 10.99;
        $nfe->tagimposto($std);

        $std = new \stdClass();
        $std->item = 1;
        $std->orig = 0;
        $std->CST = '00';
        $std->modBC = 0;
        $std->vBC = '0.20';
        $std->pICMS = '18.0000';
        $std->vICMS = '0.04';
        $nfe->tagICMS($std);

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
        $std->vCOFINS = 0;
        $std->vBC = 0;
        $std->pCOFINS = 0;

        $nfe->tagCOFINSST($std);

        $std = new \stdClass();
        $std->item = 1;
        $std->CST = '01';
        $std->vBC = 0;
        $std->pCOFINS = 0;
        $std->vCOFINS = 0;
        $std->qBCProd = 0;
        $std->vAliqProd = 0;
        $nfe->tagCOFINS($std);

        $std = new \stdClass();
        $std->vBC = '0.20';
        $std->vICMS = 0.04;
        $std->vICMSDeson = 0.00;
        $std->vBCST = 0.00;
        $std->vST = 0.00;
        $std->vProd = 10.99;
        $std->vFrete = 0.00;
        $std->vSeg = 0.00;
        $std->vDesc = 0.00;
        $std->vII = 0.00;
        $std->vIPI = 0.00;
        $std->vPIS = 0.00;
        $std->vCOFINS = 0.00;
        $std->vOutro = 0.00;
        $std->vNF = 11.03;
        $std->vTotTrib = 0.00;
        $nfe->tagICMSTot($std);

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
        $std->vPag = 10.99;
        $std->indPag=0;
        $nfe->tagdetPag($std);

        $xml = $nfe->getXML(); // O conteúdo do XML fica armazenado na variável $xml

        try {
            $st = new Standardize();
            $std = $st->toArray($xml);

            $nfe = new NfeModel();
            $nfe->infNFe = $std['infNFe'];
            $nfe->save();

            return response()->json($std, 200);

        } catch (\Exception $e) {
            exit($e->getMessage());
        }
    }
}
