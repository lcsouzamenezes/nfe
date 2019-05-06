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
use App\NfeModel;
use NFePHP\Common\Exception\ValidatorException;

class ListarXmlNotaFiscal
{
    public function index(Request $request, $id)
    {
        $xmlId = NfeModel::where('infNFe.attributes.Id', $id)->get()->toArray();
        $keyXml = $xmlId[0]['infNFe']['attributes']['Id'].'.xml';

        $s3 = new \Aws\S3\S3Client([
            'version' => 'latest',
            'region'  => env("S3_REGION"),
            'endpoint' => env('MINIO_ENDPOINT', 'http://minio:9000'),
            'use_path_style_endpoint' => true,
            'credentials' => [
                'key'    => env('S3_KEY'),
                'secret' => env('S3_SECRET'),
            ],
        ]);

        $result = $s3->getObject([
            'Bucket' => env('S3_BUCKET'),
            'Key'    => $keyXml,
        ]);

        header("Content-Type: {$result['ContentType']}");
        header('Content-Disposition: attachment; filename="'.$keyXml.'" ');

        echo $result['Body'];
    }
}
