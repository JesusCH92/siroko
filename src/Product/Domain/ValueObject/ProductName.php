<?php

declare(strict_types=1);

namespace App\Product\Domain\ValueObject;

use App\Product\Domain\Exception\InvalidName;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class ProductName
{
    private const int LENGTH_MAX = 200;

    #[ORM\Column(name: 'name', type: Types::STRING, length: self::LENGTH_MAX, nullable: false)]
    private string $value;

    private function __construct(?string $value)
    {
        $this->saveIfIsAllowed($value);
        $this->value = $value;
    }

    private function saveIfIsAllowed(?string $value): void
    {
        if (in_array($value, ['', null], true)) {
            throw new InvalidName('Name is required');
        }

        if (strlen($value) > self::LENGTH_MAX) {
            throw new InvalidName(sprintf('%s is greater than %s characters', $value, self::LENGTH_MAX));
        }
    }

    public static function fromString(?string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}
