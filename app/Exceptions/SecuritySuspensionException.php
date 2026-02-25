<?php

namespace App\Exceptions;

use Exception;

class SecuritySuspensionException extends Exception
{
    protected $message = 'Conta suspensa por motivos de segurança';
}
