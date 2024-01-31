<?php

declare(strict_types=1);

namespace App\Tests\Controller\Order;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Tests\Controller\AbstractApiTestCase;
use App\Tests\DataFixtures\OrderFixtures;
use Symfony\Component\HttpFoundation\Response;

class GetOrderControllerTest extends AbstractApiTestCase
{
    protected function loadFixtures(): array
    {
        return [
            new OrderFixtures(),
        ];
    }

    public function testOrderGetAction(): void
    {
        $this->client->request('GET', '/api/v1/orders/1', server: ['CONTENT_TYPE' => 'application/json', 'HTTP_X-API-Token' => self::DEVELOPER_API_TOKEN]);
        $this->assertResponseIsSuccessful();
        $expected = [
            'status' => 'ok',
            'data' => [
                'id' => 1,
                'currency' => 'GBP',
                'date' => '01/01/2016',
                'products' => [
                    ['product' => 'Rimmel Lasting Finish Lipstick 4g', 'price' => 499],
                    ['product' => 'Sebamed Shampoo 200ml', 'price' => 499],
                ],
                'total' => 998
            ]
        ];
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($expected, $response);
    }

    public function testOrderGetActionError(): void
    {
        $this->client->request('GET', '/api/v1/orders/404', server: ['CONTENT_TYPE' => 'application/json', 'HTTP_X-API-Token' => self::DEVELOPER_API_TOKEN]);
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