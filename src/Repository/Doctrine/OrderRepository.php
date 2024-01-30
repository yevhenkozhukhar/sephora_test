<?php

declare(strict_types=1);

namespace App\Repository\Doctrine;

use App\DTO\Request\ListRequestDTO;
use App\Entity\Order;
use App\Repository\OrderRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function findById(int $id): ?Order
    {
        return $this->entityManager->getRepository(Order::class)->find($id);
    }

    public function add(Order $order): void
    {
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }

    public function delete(Order $order): void
    {
        $this->entityManager->remove($order);
        $this->entityManager->flush();
    }

    public function list(ListRequestDTO $listRequestDTO): array
    {
        return $this->entityManager->getRepository(Order::class)->findBy([], null, $listRequestDTO->limit, $listRequestDTO->offset());
    }
}
