<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class DistribuicaoCommand extends Command
{
    protected $signature = "distribuicao:get {ambiente} {certId}";

    protected $description = "Recuperar as NF-e";


    public function handle()
    {
        $ambiente = $this->argument("ambiente");
        $certId = $this->argument("certId");
        dd($certId, $ambiente);

        /* $this->info("All posts have been deleted"); */
        /* $this->error("An error occurred"); */
        return;
    }
}
