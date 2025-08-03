<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject;

use App\Order\Domain\Exception\InvalidQuantity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class OrderItemQuantity
{
    private const int MIN = 1;
    private const int MAX = 100_000;

    #[ORM\Column(name: 'quantity', type: Types::INTEGER, nullable: false, options: ['unsigned' => true])]
    private int $value;

    private function __construct(?int $value)
    {
        $this->saveIfIsAllowed($value);
        $this->value = $value;
    }

    private function saveIfIsAllowed(?int $value): void
    {
        if (null === $value) {
            throw new InvalidQuantity('Quantity cannot be null');
        }

        if ($value < self::MIN || $value > self::MAX) {
            throw new InvalidQuantity(sprintf('Quantity must be between %s - %s', self::MIN, self::MAX));
        }
    }

    public static function fromInteger(?int $value): self
    {
        return new self($value);
    }

    public function value(): int
    {
        return $this->value;
    }
}
