<?php

namespace App\Nfe\Exceptions;

use Exception;

class NfeException extends Exception
{
    public function __construct($message = null, $code = 0, Exception $previous = null, $obj)
    {
        $this->obj = $obj;
    }

    public function getObj()
    {
        return $this->obj;
    }
}
