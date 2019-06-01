<?php

namespace App\Nfe\Http\Controllers\Admin;

use App\Nfe\Services\CadastrarCertificado as CadastrarCertificadoServicea;
use Illuminate\Http\Request;
use NFePHP\Common\Exception\ValidatorException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class CadastrarCertificadoTeste
{
    public function get(Request $request)
    {

        try {
            $input = $request->all();

            $service = new CadastrarCertificadoService();
            $service->cadastrar($input);

            return response()->json('Cadastrado com sucesso', 200);
        } catch (ValidatorException $e) {
            return response()->json($e->getMessage(), 400);
        }

    }

//    public function getOLD(Request $request)
//    {
//        $file = $request['path'];
//
//        if ($request->hasFile('path') && $request->file('path')->isValid()) {
//
//            Storage::disk('local')
//                ->put($file->getClientOriginalName(), File::get($file));
//        }
//    }
}
