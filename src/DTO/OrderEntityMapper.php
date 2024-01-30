<?php

namespace App\DTO;

use App\Entity\Order;
use App\Entity\OrderItem;

final class OrderEntityMapper
{
    public function readDTO(OrderDTO $orderDTO, ?Order $order = null): Order
    {
        $order ??= new Order();

        if ($orderDTO->total()) {
            $order->setTotal($orderDTO->total());
        }
        if ($orderDTO->products()) {
            $order->setProducts($this->readProducts($orderDTO, $order));
        }
        if ($orderDTO->currency()) {
            $order->setCurrency($orderDTO->currency());
        }
        if ($orderDTO->date()) {
            $order->setDate($orderDTO->date());
        }

        return $order;
    }

    public function writeDTO(Order $order): OrderDTO
    {
        return new OrderDTO(
            $order->getCurrency(),
            $order->getDate()?->format(Order::DATE_FORMAT),
            $order->getTotal(),
            $this->writeProducts($order),
            $order->getId(),
        );
    }

    private function readProducts(OrderDTO $orderDTO, $order): array
    {
        $products = [];
        foreach ($orderDTO->products() as $product) {
            assert($product instanceof OrderProductDTO);
            $orderProduct = new OrderItem();
            $orderProduct
                ->setProduct($product->product())
                ->setPrice($product->price());
            $orderProduct->setRelatedOrder($order);
            $products[] = $orderProduct;
        }

        return $products;
    }

    private function writeProducts(Order $order): array
    {
        $products = [];
        foreach ($order->getProducts() as $orderProduct) {
            $products[] = (new OrderProductDTO(
                $orderProduct->getProduct(),
                $orderProduct->getPrice(),
            ))->jsonSerialize();
        }

        return $products;
    }
}