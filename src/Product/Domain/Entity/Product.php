<?php

declare(strict_types=1);

namespace App\Product\Domain\Entity;

use App\Common\Domain\ValueObject\Metadata;
use App\Product\Domain\ValueObject\ProductName;
use App\Product\Domain\ValueObject\ProductPrice;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'product')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Embedded(class: ProductName::class, columnPrefix: false)]
    private ProductName $name;

    #[ORM\Embedded(class: ProductPrice::class, columnPrefix: false)]
    private ProductPrice $price;

    #[ORM\Embedded(class: Metadata::class, columnPrefix: false)]
    private Metadata $metadata;

    private function __construct(?string $name, ?float $price, ?DateTimeImmutable $createdAt = null)
    {
        $this->name = ProductName::fromString($name);
        $this->price = ProductPrice::fromFloat($price);
        $this->metadata = new Metadata($createdAt);
    }

    public static function create(?string $name, ?float $price, ?DateTimeImmutable $createdAt = null): self
    {
        return new self(name: $name, price: $price, createdAt: $createdAt);
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): ProductName
    {
        return $this->name;
    }

    public function price(): ProductPrice
    {
        return $this->price;
    }

    public function metadata(): Metadata
    {
        return $this->metadata;
    }
}
