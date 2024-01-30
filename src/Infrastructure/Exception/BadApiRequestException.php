<?php

namespace App\Infrastructure\Exception;

use Exception;

class BadApiRequestException extends Exception
{
    protected $code = 400;
}
