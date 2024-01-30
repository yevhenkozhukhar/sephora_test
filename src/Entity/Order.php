<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity]
#[ORM\Table(name: 'sales_order')]
class Order implements JsonSerializable
{
    public const DATE_FORMAT = 'd/m/Y';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 3)]
    private ?string $currency = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $date = null;

    #[ORM\Column]
    private ?int $total = null;

    #[ORM\OneToMany(mappedBy: 'relatedOrder', targetEntity: OrderItem::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): static
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(OrderItem $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setRelatedOrder($this);
        }

        return $this;
    }

    public function removeProduct(OrderItem $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getRelatedOrder() === $this) {
                $product->setRelatedOrder(null);
            }
        }

        return $this;
    }

    public function setProducts(array $products): static
    {
        $this->products = new ArrayCollection($products);

       return $this;
    }

    public function getProductsTotal(): int
    {
        $productsTotal = 0;
        foreach ($this->getProducts() as $product) {
            $productsTotal += $product->getPrice();
        }

        return $productsTotal;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'currency'  => $this->currency,
            'date' => $this->date?->format(self::DATE_FORMAT),
            'products'  => $this->products,
            'total'  => $this->total,
        ];
    }
}
