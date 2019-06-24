<?php

namespace App\Nfe\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Carbon\Carbon;

/**
 * Class Cert
 * @todo enviar para o Modulo Certificado
 */
class Cert extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'cert';
    protected $dates = ['dhResp'];

    public function getDhResp()
    {
        $date = Carbon::create($this->dhResp->toDateTimeString());
        $date->setTimezone('America/Sao_Paulo');

        return $date->toDateTimeString();
    }
}
