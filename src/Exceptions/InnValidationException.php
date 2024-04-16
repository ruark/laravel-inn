<?php

namespace Ruark\LaravelInn\Exceptions;

use Exception;
use Throwable;

class InnValidationException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
