<?php

namespace app\Certificado\Services;

use \App\Nfe\Models\Cert;
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
        $cadastrar->path = $file->getClientOriginalName();
        $cadastrar->save();

        Storage::disk('local')
            ->put($file->getClientOriginalName(), File::get($file));
    }
}
