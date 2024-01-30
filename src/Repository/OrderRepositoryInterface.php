<?php

namespace App\Repository;

use App\DTO\Request\ListRequestDTO;
use App\Entity\Order;

interface OrderRepositoryInterface
{
    public function findById(int $id): ?Order;

    public function add(Order $order): void;

    public function delete(Order $order): void;

    public function list(ListRequestDTO $listRequestDTO): array;
}
