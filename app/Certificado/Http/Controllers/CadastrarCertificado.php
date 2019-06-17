<?php

namespace App\Certificado\Http\Controllers;

use App\Certificado\Services\CadastrarCertificado as CadastrarCertificadoService;
use Illuminate\Http\Request;
use NFePHP\Common\Exception\ValidatorException;

class CadastrarCertificado
{
    public function cadastrar(Request $request)
    {
        $file = $request['path'];

        if ($file->getClientOriginalExtension() != 'pfx') {
            return response()->json('Somente arquivo .pfx', 400);
        }

        try {
            $input = $request->all();

            $service = new CadastrarCertificadoService();
            $service->cadastrar($input);

            return response()->json('Cadastrado com sucesso', 200);
        } catch (ValidatorException $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
}
