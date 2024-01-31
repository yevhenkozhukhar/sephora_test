<?php

declare(strict_types=1);

namespace App\Tests\Controller\Order;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Tests\Controller\AbstractApiTestCase;
use App\Tests\DataFixtures\OrderFixtures;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
        $this->client->request(Request::METHOD_DELETE, '/api/v1/orders/1', server: ['CONTENT_TYPE' => 'application/json', 'HTTP_X-API-Token' => self::DEVELOPER_API_TOKEN]);
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testDeleteOrderForbidden(): void
    {
        $this->client->request(Request::METHOD_DELETE, '/api/v1/orders/1', server: ['CONTENT_TYPE' => 'application/json', 'HTTP_X-API-Token' => self::ADMIN_API_TOKEN]);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testDeleteUnauthorizedException(): void
    {
        $this->client->request(Request::METHOD_DELETE, '/api/v1/orders/1', server: ['CONTENT_TYPE' => 'application/json']);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testDeleteOrderNotExistsError(): void
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