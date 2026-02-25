<?php

namespace App\Exceptions;

use Exception;

class DuplicatePurchaseException extends Exception
{
    protected $message = 'Compra duplicada detectada';
}
