<?php

namespace App\Nfe\Http\Controllers;

use App\Nfe\Models\NFe as NFeModel;
use App\Nfe\Services\ListarNFe as ListarNFeService;

class ListarNFe
{
    public function listarNotas($usuario)
    {

        $service = new ListarNFeService();
        $notas = $service->listarNFe($usuario);

        if (empty($notas)) {
            return response()->json('Dados não encontrados', 400);
        }
            return response()->json($notas, 200);
    }
}
