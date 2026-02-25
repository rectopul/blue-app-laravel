<?php

namespace App\Exceptions;

use Exception;

class PackageNotAvailableException extends Exception
{
    protected $message = 'Pacote não disponível ou inativo';
}
