<?php

namespace App\Exception\Order;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class MismatchedTotalPriceException extends Exception
{
    protected $message = 'Total price not equal products sum total';
    protected $code = Response::HTTP_UNPROCESSABLE_ENTITY;
}
