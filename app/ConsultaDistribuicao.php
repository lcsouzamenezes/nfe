<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Carbon\Carbon;

class ConsultaDistribuicao extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'consultaDistribuicao';
    protected $dates = ['dhResp'];

    public function getDhResp()
    {
        $date = Carbon::create($this->dhResp->toDateTimeString());
        $date->setTimezone('America/Sao_Paulo');

        return $date->toDateTimeString();
    }
}
