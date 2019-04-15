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

class BuscarDandosNFE
{
    public function buscarDadosNfe($nNF)
    {
        $nota = NfeModel::where('infNFe.ide.nNF', $nNF)->get();
        return $nota;
    }
}