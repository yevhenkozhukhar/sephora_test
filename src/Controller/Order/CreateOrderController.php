<?php

declare(strict_types=1);

namespace App\Controller\Order;

use App\DTO\OrderDTO;
use App\Exception\Order\MismatchedTotalPriceException;
use App\Infrastructure\Exception\BadApiRequestException;
use App\Infrastructure\Reponse\ApiResponseHelper;
use App\Infrastructure\Resolver\RequestValidateValueResolver;
use App\Service\OrderManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class CreateOrderController
{
    #[Route('/api/v1/orders', name: 'api_orders_create', methods: Request::METHOD_POST, format: 'json')]
    public function _invoke(
        #[MapRequestPayload(acceptFormat: 'json', validationGroups: ['create', 'Default'], resolver: RequestValidateValueResolver::class)] OrderDTO $orderDTO,
        OrderManagerInterface $orderManager
    ): JsonResponse {
        try {
            $orderManager->addOrder($orderDTO);
        } catch (MismatchedTotalPriceException $exception) {
            throw new BadApiRequestException($exception->getMessage(), $exception->getCode());
        }

        return ApiResponseHelper::emptySuccessResponse();
    }
}
