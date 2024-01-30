<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Order;
use DateTime;
use DateTimeInterface;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class OrderDTO implements JsonSerializable
{
    public function __construct(
        #[Assert\Length(3)]
        #[Assert\NotBlank(groups: ['create'])]
        private ?string $currency,
        #[Assert\DateTime(format: Order::DATE_FORMAT)]
        #[Assert\NotBlank(groups: ['create'])]
        private ?string $date,
        #[Assert\Range(['min' => 1])]
        #[Assert\NotBlank(groups: ['create'])]
        private ?int $total,
        #[Assert\All(
            constraints: [
                new Assert\Collection(
                    fields: [
                        'product' => [
                            new Assert\NotBlank(),
                        ],
                        'price' => [
                            new Assert\NotBlank(),
                            new Assert\GreaterThan(0),
                        ],
                    ],
                    allowExtraFields: true,
                ),
            ]
        )]
        #[Assert\NotBlank(groups: ['create'])]
        private ?array $products = [],
        private ?int $id = null,
    ) {
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function date(): ?DateTimeInterface
    {
        if (!$this->date) {
            return null;
        }

        return DateTime::createFromFormat(Order::DATE_FORMAT, $this->date);
    }

    public function products(): array
    {
        $products = [];
        foreach ($this->products as $product) {
            $products[] = OrderProductDTO::fromArray($product);
        }

        return $products;
    }

    public function getProductsTotal(): int
    {
        $productsTotal = 0;
        foreach ($this->products() as $product) {
            assert($product instanceof OrderProductDTO);
            $productsTotal += $product->price();
        }

        return $productsTotal;
    }

    public function total(): int
    {
        return $this->total;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'currency'  => $this->currency,
            'date' => $this->date()?->format(Order::DATE_FORMAT),
            'products'  => array_map(function (OrderProductDTO $item) {
                return $item->jsonSerialize();
            }, $this->products()),
            'total'  => $this->total,
        ];
    }
}
