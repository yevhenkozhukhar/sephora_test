<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

final readonly class OrderProductDTO implements JsonSerializable
{
    public function __construct(
        private string $product,
        private int $price,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['product'],
            $data['price'],
        );
    }

    public function product(): string
    {
        return $this->product;
    }

    public function price(): int
    {
        return $this->price;
    }

    public function jsonSerialize(): array
    {
        return [
            'product' => $this->product(),
            'price' => $this->price,
        ];
    }
}
