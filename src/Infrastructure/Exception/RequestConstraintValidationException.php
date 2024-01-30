<?php

namespace App\Infrastructure\Exception;

use Exception;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class RequestConstraintValidationException extends Exception
{
    private const ERROR_MESSAGE = 'Validation error';

    private ?ConstraintViolationListInterface $constraintViolationList = null;

    public static function createFromConstraintViolationList(
        ConstraintViolationListInterface $violationList,
    ): RequestConstraintValidationException {
        $self = new self(self::ERROR_MESSAGE);
        $self->constraintViolationList = $violationList;

        return $self;
    }

    public function constraintViolationList(): ConstraintViolationListInterface
    {
        return $this->constraintViolationList ?? new ConstraintViolationList();
    }
}