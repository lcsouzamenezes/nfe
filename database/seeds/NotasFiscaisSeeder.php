<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotasFiscaisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::collection('notas_fiscais')->truncate();
        factory(App\NotasFiscaisModel::class, 15)->create();
    }
}
