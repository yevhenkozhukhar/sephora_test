<?php

declare(strict_types=1);

namespace App\Infrastructure\Reponse;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseHelper
{
    final public const SUCCESS = 'ok';

    final public const ERROR = 'error';

    public const ERROR_PARAMETER_VALIDATION_MESSAGE = 'Validation errors.';

    public static function successResponse(?array $data, int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return new JsonResponse(['status' => self::SUCCESS, 'data' => $data], $statusCode);
    }

    public static function emptySuccessResponse(int $statusCode = Response::HTTP_CREATED): JsonResponse
    {
        return new JsonResponse(null, $statusCode);
    }

    public static function errorResponse(
        string $message,
        ?array $errors = null,
        int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR,
    ): JsonResponse {
        $response = ['status' => self::ERROR, 'message' => $message];
        if ($errors) {
            $response['errors'] = $errors;
        }

        return new JsonResponse($response, $statusCode);
    }

    public static function validationErrorResponse(array $errors): JsonResponse
    {
        return self::errorResponse(
            self::ERROR_PARAMETER_VALIDATION_MESSAGE,
            $errors,
            Response::HTTP_UNPROCESSABLE_ENTITY,
        );
    }
}
