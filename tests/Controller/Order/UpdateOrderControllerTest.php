<?php

declare(strict_types=1);

namespace App\Tests\Controller\Order;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Tests\Controller\AbstractApiTestCase;
use App\Tests\DataFixtures\OrderFixtures;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateOrderControllerTest extends AbstractApiTestCase
{
    protected function loadFixtures(): array
    {
        return [
            new OrderFixtures(),
        ];
    }

    public function testAddAction(): void
    {
        $data = [
            'currency' => 'GBP',
            'date' => '01/01/2023',
            'products' => [
                ['product' => 'Test product 1', 'price' => 300],
                ['product' => 'Test product 2', 'price' => 450],
            ],
            'total' => 750
        ];

        $this->client->request(Request::METHOD_PUT, '/api/v1/orders/1', server: ['CONTENT_TYPE' => 'application/json'], content: json_encode($data));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $data['id'] = 1;
        $this->assertEquals($data, $response['data']);
    }

    public function truncateEntities(): array
    {
        return [
            OrderItem::class,
            Order::class,
        ];
    }
}
