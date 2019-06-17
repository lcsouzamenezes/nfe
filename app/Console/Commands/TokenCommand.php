<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use \Firebase\JWT\JWT;

class TokenCommand extends Command
{
    protected $signature = "token:get {certId} {nome}";

    protected $description = "Recuperar as NF-e";


    public function handle()
    {
        $certId = $this->argument("certId");
        $nome = $this->argument("nome");


        /* dd($certId, $ambiente); */

        $key = '7Fsxc2A865V6';

        $dados = [
            'auth' => [
                'usu_codigo' => 1,
                'usu_nome' => $nome,
                'usu_identificacao' => $certId
            ],
            'grupoAtivo' => 93
        ];

        $issuedAt = time();
        $expire = $issuedAt + 1000000;

        $tokenParam = [
            'iat'  => $issuedAt,
            /* 'iss'  => $options['iss'], */
            'exp'  => $expire,
            'nbf'  => $issuedAt - 1,
            'data' => $dados,
        ];
        $this->info(JWT::encode($tokenParam, $key));

        /* return JWT::encode($tokenParam, $key); */
    }
}
