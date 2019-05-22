<?php

namespace App\Nfe\Http\Controllers;

use App\Nfe\Models\NFe as NFeModel;
use App\Nfe\Services\BuscarNFe as BuscarNFeService;

class BuscarDadosNFe
{
    public function buscarNFe($id)
    {
        $service = new BuscarNFeService();
        $dadosNFe = $service->buscarNFe($id);

        if (empty($dadosNFe)) {
            return response()->json('Dados não encontrados', 400);
        }

        return response()->json($dadosNFe, 200);
    }
}
