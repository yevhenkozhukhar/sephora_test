<?php

declare(strict_types=1);

namespace App\Tests\Controller\Order;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Tests\Controller\AbstractApiTestCase;

class OrderListControllerTest extends AbstractApiTestCase
{

    public function testGetOrders(): void
    {
        $this->client->request('GET', '/api/v1/orders', server: ['CONTENT_TYPE' => 'application/json', 'HTTP_X-API-Token' => self::DEVELOPER_API_TOKEN]);
        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
        $this->assertJson($this->client->getResponse()->getContent());
    }

    protected function truncateEntities(): array
    {
        return [
            OrderItem::class,
            Order::class,
        ];
    }
}
