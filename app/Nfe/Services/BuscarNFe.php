<?php

namespace app\Nfe\Services;

use \App\Nfe\Models\NFe as NFeModel;

class BuscarNFe
{
    public function buscarNFe($chNFe)
    {
        $dadosNota = NFeModel::where('_id', $chNFe)
            ->orWhere('chNFe', $chNFe)
            ->get()
            ->toArray();
        return $dadosNota;
    }
}
