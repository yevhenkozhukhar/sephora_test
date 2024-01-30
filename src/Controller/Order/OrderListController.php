<?php

declare(strict_types=1);

namespace App\Controller\Order;

use App\DTO\Request\ListRequestDTO;
use App\Infrastructure\Resolver\RequestValidateValueResolver;
use App\Repository\Doctrine\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class OrderListController extends AbstractController
{
    #[Route('/api/v1/orders', name: 'api_orders_list', methods: Request::METHOD_GET, format: 'json')]
    public function _invoke(
        #[MapQueryString(resolver: RequestValidateValueResolver::class)] ?ListRequestDTO $listRequestDTO,
        OrderRepository $orderRepository,
    ): JsonResponse {
        $orders = $orderRepository->list($listRequestDTO ?? new ListRequestDTO());

        return $this->json(['orders' => $orders]);
    }
}
