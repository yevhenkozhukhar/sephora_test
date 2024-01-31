<?php

declare(strict_types=1);

namespace App\Infrastructure\EventListener;

use App\Infrastructure\Exception\BadApiRequestException;
use App\Infrastructure\Exception\RequestConstraintValidationException;
use App\Infrastructure\Reponse\ApiResponseHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ApiExceptionListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'processException',
        ];
    }

    public function processException(ExceptionEvent $event): void
    {
        $path = $event->getRequest()->getPathInfo();
        if (!str_starts_with($path, '/api/')) {
            return;
        }
        $error = $event->getThrowable();

        $response = match (get_class($error)) {
            RequestConstraintValidationException::class => ApiResponseHelper::validationErrorResponse(
                $this->transformViolationListToArray($error->constraintViolationList())
            ),
            BadApiRequestException::class => ApiResponseHelper::errorResponse($error->getMessage(), [], $error->getCode()),
            default => ApiResponseHelper::errorResponse($error->getMessage()),
        };

        if ($response) {
            $event->setResponse($response);
        }
    }

    private function transformViolationListToArray(ConstraintViolationListInterface $constraintViolationList): array
    {
        $errorsList = [];
        foreach ($constraintViolationList as $error) {
            $propertyPath = $error->getPropertyPath();
            $errorsList[$propertyPath] = $error->getMessage();
        }

        return $errorsList;
    }
}