<?php

namespace App\Exceptions;

use Exception;

class RateLimitExceededException extends Exception
{
    protected $message = 'Limite de tentativas excedido';
}
