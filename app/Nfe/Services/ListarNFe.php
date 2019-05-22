<?php

namespace app\Nfe\Services;

use \App\Nfe\Models\NFe as NFeModel;

class ListarNFe
{
    public function listarNFe($usuario)
    {
        $usuario = NFeModel::where('data.NFe.infNFe.dest.CPF', $usuario)
            ->orWhere('data.NFe.infNFe.dest.CNPJ', $usuario)
            ->get()
            ->toArray();

        return $usuario;
    }
}
