<?php

namespace App\Service;

use App\DTO\OrderDTO;

interface OrderManagerInterface
{
    public function addOrder(OrderDTO $orderDTO): void;

    public function updateOrder(int $id, OrderDTO $orderDTO): OrderDTO;

    public function deleteOrder(int $id): void;

    public function findById(int $id): OrderDTO;
}
