<?php

declare(strict_types=1);

namespace App\Cart\ApplicationService\DTO;

use App\User\Domain\Entity\User;
use DateTimeInterface;

final readonly class ProductToCartAdderRequest
{
    public function __construct(
        public int  $productId,
        public int  $quantity,
        public User $user,
        public ?DateTimeInterface $createdAt = null
    )
    {
    }
}
