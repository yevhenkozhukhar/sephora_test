<?php

declare(strict_types=1);

namespace App\Tests\Controller\Order;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Tests\Controller\AbstractApiTestCase;
use App\Tests\DataFixtures\OrderFixtures;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteOrderControllerTest extends AbstractApiTestCase
{
    protected function loadFixtures(): array
    {
        return [
            new OrderFixtures(),
        ];
    }

    public function testDeleteOrder(): void
    {
        $this->client->request(Request::METHOD_DELETE, '/api/v1/orders/1');
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testDeleteOrderNotExistsError(): void
    {
        $this->client->request('GET', '/api/v1/orders/404');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    protected function truncateEntities(): array
    {
        return [
            OrderItem::class,
            Order::class,
        ];
    }
}