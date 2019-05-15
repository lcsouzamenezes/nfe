<?php

namespace App\Nfe\Http\Controllers;

use App\Nfe\Models\Cert;

use Illuminate\Http\Response;
use Illuminate\Http\Request;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $cert = null;

    public function __construct()
    {
        $this->cert = new Cert();
    }
}
