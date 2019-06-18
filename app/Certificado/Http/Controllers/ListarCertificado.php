<?php

namespace App\Certificado\Http\Controllers;

use App\Certificado\Services\ListarCertificado as ListarCertificadoService;
use NFePHP\Common\Exception\ValidatorException;

class ListarCertificado
{
    public function listar()
    {
        try {
            $serviceCert = new ListarCertificadoService();
            $service = $serviceCert->listarCertificado();

            return response()->json($service, 200);
        } catch (ValidatorException $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
}
