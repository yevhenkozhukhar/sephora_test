<?php

declare(strict_types=1);

namespace App\EventListener\Doctrine;

use App\Entity\Order;
use App\Exception\Order\MismatchedTotalPriceException;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postPersist, method: 'validateTotal', entity: Order::class)]
class OrderSumChecker
{
    public function validateTotal(Order $order, PostPersistEventArgs $event): void
    {
        if ($order->getProductsTotal() !== $order->getTotal()) {
            throw new MismatchedTotalPriceException();
        }
    }
}
