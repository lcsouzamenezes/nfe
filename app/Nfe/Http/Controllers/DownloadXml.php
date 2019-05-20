<?php

namespace App\Nfe\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use NFePHP\NFe\Make;
use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Complements;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Nfe\Models\NFe as NFeModel;
use App\Nfe\Services\DownloadXml as DownloadXmlService;
use NFePHP\Common\Exception\ValidatorException;

class DownloadXml
{
    public function get(Request $request, $chNFe)
    {
        $service = new DownloadXmlService();
        $service->downloadXml($chNFe);
    }
}
