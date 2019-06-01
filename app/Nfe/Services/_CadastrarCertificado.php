<?php

namespace app\Nfe\Services;

use \App\Nfe\Models\Cert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CadastrarCertificado
{
    public function cadastrar($request)
    {
        $file = $request['path'];

        $cadastrar = new Cert();
        $cadastrar->razaosocial = $request['razaosocial'];
        $cadastrar->cnpj = $request['cnpj'];
        $cadastrar->tpAmb = $request['tpAmb'];
        $cadastrar->pass = $request['pass'];
        $cadastrar->path = $request['path'];
        $cadastrar->path = $file->getClientOriginalName();
        $cadastrar->save();

        Storage::disk('local')
            ->put($file->getClientOriginalName(), File::get($file));
    }

//    public function storeFile($request)
//    {
//        $path = $request->file('path');
//
//        if ($request->file('path')->isValid()) {
//                $request->file('path')->move();
////               Storage::disk('local')
////                    ->put($file->getClientOriginalName(), File::get($file));
//            }
//    }
}
