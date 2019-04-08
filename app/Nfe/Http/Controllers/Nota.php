<?php

namespace App\Nfe\Http\Controllers;

use NFePHP\NFe\Make;
use NFePHP\NFe\Tools as Tools;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Complements;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use \App\Nfe\Exceptions\NfeException;

class Nota
{
    public function index(Request $request)
    {
        $return = [
            0 => [
                'id' => 1,
                'data' => 2,
                'destinatario' => 'Teste Inc'
            ],
            1 => [
                'id' => 1,
                'data' => 2,
                'destinatario' => 'Teste Inc'
            ]
        ];

        return response()->json($return, 200);
    }
}
