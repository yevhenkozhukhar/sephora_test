<?php

namespace App\Tests\DataFixtures;

use App\Entity\Order;
use App\Entity\OrderItem;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrderFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getOrders() as $item) {
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

            $manager->persist($order);
        }

        $manager->flush();

    }

    private function getOrders(): array
    {
        return [
            [
                'currency' => 'GBP',
                'date' => '01/01/2016',
                'products' => [
                    ['title' => 'Rimmel Lasting Finish Lipstick 4g', 'price' => 499],
                    ['title' => 'Sebamed Shampoo 200ml', 'price' => 499],
                ],
                'total' => 998
            ],
            [
                'currency' => 'EUR',
                'date' => '02/01/2016',
                'products' => [
                    ['title' => 'GHD Hair Straighteners', 'price' => 9999],
                    ['title' => 'Redken Shampure Shampoo', 'price' => 1999],
                ],
                'total' => 11998,
            ],
            [
                'currency' => 'EUR',
                'date' => '01/01/2024',
                'products' => [
                    ['title' => 'Sephora collection makeup', 'price' => 1800],
                    ['title' => 'Sephora skincare', 'price' => 1900],
                ],
                'total' => 3700,
            ],
        ];
    }
}
