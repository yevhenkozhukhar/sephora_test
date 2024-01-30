<?php

declare(strict_types=1);

namespace App\Controller\Order;

use App\Exception\Order\OrderNotExistException;
use App\Infrastructure\Exception\BadApiRequestException;
use App\Infrastructure\Reponse\ApiResponseHelper;
use App\Service\OrderManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class GetOrderController
{
    #[Route('/api/v1/orders/{id}', name: 'api_order_get', methods: Request::METHOD_GET, format: 'json')]
    public function _invoke(int $id, OrderManagerInterface $orderManager): JsonResponse
    {
        try {
            $order = $orderManager->findById($id);
        } catch (OrderNotExistException) {
            throw new BadApiRequestException('Order not found', Response::HTTP_NOT_FOUND);
        }

        return ApiResponseHelper::successResponse($order->jsonSerialize());
    }
}
