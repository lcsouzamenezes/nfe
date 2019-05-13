<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class NotasFiscaisModel extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'notas_fiscais';
}
