<?php

declare(strict_types=1);

namespace App\Tests\Controller\Order;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Tests\Controller\AbstractApiTestCase;
use phpDocumentor\Reflection\Types\Self_;
use Symfony\Component\HttpFoundation\Response;

class CreateOrderControllerTest extends AbstractApiTestCase
{
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

        $this->client->request(
            'POST',
            '/api/v1/orders',
            server: ['CONTENT_TYPE' => 'application/json', 'HTTP_X-API-Token' => self::DEVELOPER_API_TOKEN],
            content: json_encode($data),
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testAddActionValidationEmptyRequest(): void
    {
        $data = [];
        //empty request
        $this->client->request('POST', '/api/v1/orders', server: ['CONTENT_TYPE' => 'application/json', 'HTTP_X-API-Token' => self::DEVELOPER_API_TOKEN], content: json_encode($data));
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $expectedErrors = [
            'currency' => 'This value should not be blank.',
            'date' => 'This value should not be blank.',
            'total' => 'This value should not be blank.',
            'products' => 'This value should not be blank.',
        ];
        $errors = json_decode($this->client->getResponse()->getContent(), true)['errors'];
        $this->assertEquals($expectedErrors, $errors);
    }

    public function testAddActionValidationErrors(): void
    {
        $data = [
            'currency' => '',
            'date' => '01-01-2023',
            'products' => [
                ['product' => 'Test product 1'],
                ['product' => 'Test product 2', 'price' => 450],
            ],
            'total' => 750
        ];
        $this->client->request('POST', '/api/v1/orders', server: ['CONTENT_TYPE' => 'application/json', 'HTTP_X-API-Token' => self::DEVELOPER_API_TOKEN], content: json_encode($data));
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $expectedErrors = [
            'currency' => 'This value should have exactly 3 characters.',
            'date' => 'This value is not a valid datetime.',
            'products[0][price]' => 'This field is missing.',
        ];
        $this->assertEquals($expectedErrors, $response['errors']);
    }

    public function testAddActionValidationTotal(): void
    {
        $data = [
            'currency' => 'GBP',
            'date' => '01/01/2023',
            'products' => [
                ['product' => 'Test product 1', 'price' => 300],
                ['product' => 'Test product 2', 'price' => 450],
            ],
            'total' => 850
        ];

        $this->client->request('POST', '/api/v1/orders', server: ['CONTENT_TYPE' => 'application/json', 'HTTP_X-API-Token' => self::DEVELOPER_API_TOKEN], content: json_encode($data));
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Total price not equal products sum total', $response['message']);
    }

    protected function truncateEntities(): array
    {
        return [
            OrderItem::class,
            Order::class,
        ];
    }
}
