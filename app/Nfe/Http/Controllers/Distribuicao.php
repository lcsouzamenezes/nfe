<?php
namespace App\Nfe\Http\Controllers;

use App\Nfe\Http\Controllers\Controller;
use App\Nfe\Services\Distribuicao as DistribuicaoService;
use \App\Nfe\Models\Distribuicao as DistribuicaoModel;

use NFePHP\NFe\Make;
use NFePHP\NFe\Tools as Tools;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Complements;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use \App\Nfe\Exceptions\NfeException;

use Carbon\Carbon;

/**
 * Class Distribuicao
 * @todo refatoração
 */
class Distribuicao extends Controller
{
    public function get(
        Request $request,
        $ambiente,
        $certId
    ) {
        $service = new DistribuicaoService();
        $tools = $service->config($certId);

        $ultimaConsulta = DistribuicaoModel::orderBy('dhResp', 'desc')->first();

        /* dd($ultimaConsulta->getDhResp()); */

        set_time_limit(600);

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

            $service->salvarConsultaDistribuicao($r);

            $lote = $node->getElementsByTagName('loteDistDFeInt')->item(0);
            if (empty($lote)) {
                //lote vazio
                continue;
            }
            //essas tags irão conter os documentos zipados
            $docs = $lote->getElementsByTagName('docZip');

            $service->salvarNFe($docs);

            sleep(2);
        }

        $data = ['data' => ['message' => 'Criado com Sucesso!']];

        return response()->json($data, 201);
    }
}
