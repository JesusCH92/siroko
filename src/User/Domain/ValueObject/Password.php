<?php

declare(strict_types=1);

namespace App\User\Domain\ValueObject;

use App\User\Domain\Exception\InvalidPassword;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Password
{
    private const int LENGTH_MAX = 255;

    #[ORM\Column(name: 'password', type: Types::STRING, length: self::LENGTH_MAX, nullable: false)]
    private string $value;

    private function __construct(?string $value)
    {
        $this->saveIfIsAllowed($value);
        $this->value = $value;
    }

    private function saveIfIsAllowed(?string $value): void
    {
        if (in_array($value, ['', null], true)) {
            throw new InvalidPassword('Password is required');
        }

        if (strlen($value) > self::LENGTH_MAX) {
            throw new InvalidPassword(sprintf('%s is greater than %s characters', $value, self::LENGTH_MAX));
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
