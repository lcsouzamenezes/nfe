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
    public function buscarDadosNfe($id)
    {
        $nota = NfeModel::where('_id', $id)->first();
        return $nota;
    }
}
