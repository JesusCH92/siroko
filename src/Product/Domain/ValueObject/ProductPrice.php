<?php

declare(strict_types=1);

namespace App\Product\Domain\ValueObject;

use App\Product\Domain\Exception\InvalidPrice;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class ProductPrice
{
    private const float MIN = 0.0;
    private const float MAX = 999_999.99;

    #[ORM\Column(name: 'price', type: Types::DECIMAL, precision: 10, scale: 2, nullable: false)]
    private string $value;

    private function __construct(?string $value)
    {
        $this->saveIfIsAllowed($value);
        $this->value = $value;
    }

    private function saveIfIsAllowed(?string $value): void
    {
        if (null === $value) {
            throw new InvalidPrice('Price cannot be null');
        }

        $floatValue = (float)$value;

        if ($floatValue < self::MIN || $floatValue > self::MAX) {
            throw new InvalidPrice(sprintf('Price must be between %s - %s', self::MIN, self::MAX));
        }
    }

    public static function fromFloat(float $value): self
    {
        return new self(number_format($value, 2, '.', ''));
    }

    public function value(): string
    {
        return $this->value;
    }

    public function toFloat(): float
    {
        return (float)$this->value;
    }
}
