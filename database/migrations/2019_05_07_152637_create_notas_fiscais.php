<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotasFiscais extends Migration
{
    protected $connection = 'mongodb';

    public function up()
    {
        if(Schema::connection($this->connection)->hasTable('notas_fiscais') == false) {
            Schema::create('notas_fiscais', function ($collection) {
                $collection->index('id');
            });
        }
    }

    public function down()
    {
        Schema::connection($this->connection)
            ->table('notas_fiscais', function (Blueprint $collection)
            {
                $collection->drop();
            });
    }
}
