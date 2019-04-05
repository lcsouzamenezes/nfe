<?php

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class NfeTest extends TestCase
{
    public function testConnection()
    {
        $connection = DB::connection('mongodb');
        $this->assertInstanceOf('Jenssegers\Mongodb\Connection', $connection);
    }

//    public function testControllerGerarNota()
//    {
//        $dados = [
//            'nNF' => 711,
//            'CNPJ' => '32944459000130',
//            'IE' => '0790366100165'
//        ];
//
//        $this->post('/gerar-nfe/2', $dados);
//
//        $this->assertEquals($dados['CNPJ'], '32944459000130');
//        $this->assertEquals($dados['IE'], '0790366100165');
//        $this->assertResponseOk();
////        $resposta = (array) json_decode($this->response->content());
//
//
//
////        $this->assertArrayHasKey('nNF', $resposta);
////        $this->assertArrayHasKey('CNPJ', $resposta);
////        $this->assertArrayHasKey('IE', $resposta);
//    }

//    function test_json_response()
//    {
//        $recibo = '533002185406617';
//        $response = $this->json('GET', '/consultar-recibo/533002185406617/2');
//
//        $response
//            ->assertResponseStatus(200);
//    }
}
