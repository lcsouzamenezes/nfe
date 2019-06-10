<?php

namespace app\Certificado\Services;

use \App\Nfe\Models\Cert;

class ListarCertificado
{
    public function listarCertificado()
    {
        $certificado = Cert::all();

        return $certificado;
    }
}
