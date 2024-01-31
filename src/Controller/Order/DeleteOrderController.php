<?php

declare(strict_types=1);

namespace App\Controller\Order;

use App\Entity\Order;
use App\Exception\Order\OrderNotExistException;
use App\Infrastructure\Exception\BadApiRequestException;
use App\Infrastructure\Reponse\ApiResponseHelper;
use App\Repository\OrderRepositoryInterface;
use App\Service\OrderManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AsController]
class DeleteOrderController
{
    #[IsGranted('ROLE_DEVELOPER')]
    #[Route('/api/v1/orders/{id}', name: 'api_orders_delete', methods: Request::METHOD_DELETE, format: 'json')]
    public function _invoke(int $id, OrderManager $orderManager): JsonResponse
    {
        try {
            $orderManager->deleteOrder($id);
        } catch (OrderNotExistException) {
            throw new BadApiRequestException('Order not found', Response::HTTP_NOT_FOUND);
        }

        return ApiResponseHelper::emptySuccessResponse(Response::HTTP_NO_CONTENT);
    }
}
