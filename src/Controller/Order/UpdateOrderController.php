<?php

declare(strict_types=1);

namespace App\Controller\Order;

use App\DTO\OrderDTO;
use App\Infrastructure\Reponse\ApiResponseHelper;
use App\Infrastructure\Resolver\RequestValidateValueResolver;
use App\Service\OrderManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class UpdateOrderController
{
    #[Route('/api/v1/orders/{id}', name: 'api_orders_update', methods: Request::METHOD_PUT, format: 'json')]
    public function __invoke(
        #[MapRequestPayload(acceptFormat: 'json', validationGroups: ['update', 'Default'], resolver: RequestValidateValueResolver::class)] OrderDTO $orderDTO,
        OrderManagerInterface $orderManager,
        int $id,
    ): JsonResponse
    {
        $order = $orderManager->updateOrder($id, $orderDTO);

        return ApiResponseHelper::successResponse($order->jsonSerialize(), Response::HTTP_OK);
    }
}
