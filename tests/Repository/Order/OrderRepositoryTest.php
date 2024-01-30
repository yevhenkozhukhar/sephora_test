<?php

declare(strict_types=1);

namespace App\Tests\Repository\Order;

use App\DTO\Request\ListRequestDTO;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\Doctrine\OrderRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OrderRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private OrderRepository $orderRepository;

    protected function setUp(): void
    {
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();
//        $this->cleanOrderTable();
        $this->orderRepository = new OrderRepository($this->entityManager);
    }

    public function testFindById(): void
    {
        $order = $this->getOrder();
        $this->orderRepository->add($order);
        $foundOrder = $this->orderRepository->findById($order->getId());

        $this->assertEquals($order, $foundOrder);
    }

    public function testAdd(): void
    {
        $order = $this->getOrder();
        $this->orderRepository->add($order);

        $foundOrder = $this->orderRepository->findById($order->getId());
        $this->assertEquals($order, $foundOrder);
    }

    public function testDelete(): void
    {
        $order = $this->getOrder();
        $this->orderRepository->add($order);
        $foundOrder = $this->orderRepository->findById($order->getId());
        $this->assertEquals($order, $foundOrder);
        $id = $foundOrder->getId();
        $this->orderRepository->delete($foundOrder);
        $foundOrder = $this->orderRepository->findById($id);
        $this->assertNull($foundOrder);
    }

    public function testList(): void
    {
        $this->cleanOrderTable();
        $orders = [$this->getOrder(), $this->getOrder()];
        foreach ($orders as $order) {
            $this->orderRepository->add($order);
        }

        $listRequestDTO = new ListRequestDTO();
        $listedOrders = $this->orderRepository->list($listRequestDTO);
        $this->assertCount(count($orders), $listedOrders);

        $listRequestDTO = new ListRequestDTO(1, 1);
        $listedOrders = $this->orderRepository->list($listRequestDTO);
        $this->assertCount(1, $listedOrders);
    }

    private function getOrder(): Order
    {
        $item = $this->getOrderData();
        $order = new Order();
        $order
            ->setCurrency($item['currency'])
            ->setDate(new DateTime($item['date']))
            ->setTotal($item['total']);

        foreach ($item['products'] as $itemProduct) {
            $product = new OrderItem();
            $product
                ->setProduct($itemProduct['title'])
                ->setPrice($itemProduct['price']);
            $product->setRelatedOrder($order);
            $order->addProduct($product);
        }

        return $order;
    }

    protected function tearDown(): void
    {
        $this->cleanOrderTable();
    }

    private function getOrderData(): array
    {
        return [
            'currency' => 'GBP',
            'date' => '02/02/2020',
            'products' => [
                ['title' => 'Demo product', 'price' => 100],
                ['title' => 'Demo product', 'price' => 200],
            ],
            'total' => 300
        ];
    }

    private function cleanOrderTable(): void
    {
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 0;');
        $connection->executeStatement($platform->getTruncateTableSQL('sales_order_item'));
        $connection->executeStatement($platform->getTruncateTableSQL('sales_order'));
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1;');
        $this->entityManager->clear();
    }
}