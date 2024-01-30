<?php

namespace App\Exception\Order;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class OrderNotExistException extends Exception
{
    public function __construct(string $message = "Order not found", int $code = Response::HTTP_NOT_FOUND, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}