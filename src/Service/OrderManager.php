<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\OrderDTO;
use App\DTO\OrderEntityMapper;
use App\Entity\Order;
use App\Exception\Order\OrderNotExistException;
use App\Repository\OrderRepositoryInterface;

readonly class OrderManager implements OrderManagerInterface
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private OrderEntityMapper $orderEntityMapper,
    ) {
    }

    public function addOrder(OrderDTO $orderDTO): void
    {
        $order = $this->orderEntityMapper->readDTO($orderDTO);
        $this->orderRepository->add($order);
    }

    public function updateOrder(int $id, OrderDTO $orderDTO): OrderDTO
    {
        $order = $this->orderRepository->findById($id);
        $order = $this->orderEntityMapper->readDTO($orderDTO, $order);
        $this->orderRepository->add($order);

        return $this->orderEntityMapper->writeDTO($order);
    }

    public function deleteOrder(int $id): void
    {
        $order = $this->findOrderById($id);

        $this->orderRepository->delete($order);
    }

    public function findById(int $id): OrderDTO
    {
        $order = $this->findOrderById($id);

        return $this->orderEntityMapper->writeDTO($order);
    }

    private function findOrderById(int $id): Order
    {
        $order = $this->orderRepository->findById($id);
        if (!$order) {
            throw new OrderNotExistException();
        }

        return $order;
    }
}
