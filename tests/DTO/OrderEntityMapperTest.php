<?php

declare(strict_types=1);

namespace App\Tests\DTO;

use App\DTO\OrderDTO;
use App\DTO\OrderEntityMapper;
use App\Entity\Order;
use App\Entity\OrderItem;
use DateTime;
use PHPUnit\Framework\TestCase;

class OrderEntityMapperTest extends TestCase
{
    private OrderEntityMapper $orderEntityMapper;

    protected function setUp(): void
    {
        $this->orderEntityMapper = new OrderEntityMapper();
    }

    public function testReadDTO(): void
    {
        $orderDTO = new OrderDTO(
            'GBP',
            '02/22/2020',
            300,
            [
                ['product' => 'Demo product', 'price' => 100],
                ['product' => 'Demo product', 'price' => 200],
            ],
        );
        $result = $this->orderEntityMapper->readDTO($orderDTO);
        $this->assertInstanceOf(Order::class, $result);
        $this->assertEquals($orderDTO->currency(), $result->getCurrency());
        $this->assertEquals($orderDTO->total(), $result->getTotal());
        $this->assertEquals($orderDTO->date(), $result->getDate());
    }

    public function testWriteDTO(): void
    {
        $product = new OrderItem();
        $product
            ->setPrice(555)
            ->setProduct('Test product');

        $order = new Order();
        $order
            ->setCurrency('GBP')
            ->setDate(new DateTime())
            ->setTotal(555)
            ->setProducts([$product]);
        $result = $this->orderEntityMapper->writeDTO($order);
        $this->assertInstanceOf(OrderDTO::class, $result);
    }
}