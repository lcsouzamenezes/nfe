<?php

namespace app\Nfe\Services;

use \App\Nfe\Models\NFe as NFeModel;

class DownloadXml
{
    public function downloadXml($chNFe)
    {
        $xmlId = NFeModel::where('chNFe', $chNFe)->get()->toArray();
        $keyXml = $xmlId[0]['chNFe'].'.xml';

        $s3 =   new \Aws\S3\S3Client([
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
