<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject;

use App\Order\Domain\Exception\InvalidPrice;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class OrderTotalAmount
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
        $floatValue = (float)$value;

        if ($floatValue < self::MIN || $floatValue > self::MAX) {
            throw new InvalidPrice(sprintf(
                'Price must be between %.2f and %.2f',
                self::MIN,
                self::MAX
            ));
        }
    }

    public static function fromFloat(float $value): self
    {
        return new self(number_format($value, 2, '.', ''));
    }

    public static function fromZero(): self
    {
        return self::fromFloat(0.0);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function toFloat(): float
    {
        return (float)$this->value;
    }

    public function increase(float $quantity): self
    {
        return self::fromFloat($this->toFloat() + $quantity);
    }
}
