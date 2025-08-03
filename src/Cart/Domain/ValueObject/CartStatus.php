<?php

declare(strict_types=1);

namespace App\Cart\Domain\ValueObject;

enum CartStatus: string
{
    case OPEN = 'open';
    case COMPLETED = 'completed';
    case ABANDONED = 'abandoned';

    public static function fromStatus(?string $value): self
    {
        if ($value === null) {
            throw new \InvalidArgumentException('Status value cannot be null.');
        }

        return match ($value) {
            self::OPEN->value => self::OPEN,
            self::COMPLETED->value => self::COMPLETED,
            self::ABANDONED->value => self::ABANDONED,
            default => throw new \InvalidArgumentException(sprintf('Invalid Status value: "%s"', $value)),
        };
    }

    public function completed(): self
    {
        return self::COMPLETED;
    }
}
