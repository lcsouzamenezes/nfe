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

class ListarNotas
{
    public function listarNotas(Request $request, $usuario)
    {
        $notas = NfeModel::all();
        $notas = NfeModel::where('data.NFe.infNFe.dest.CPF', '=', $usuario)->get();
        return $notas;
    }
}
