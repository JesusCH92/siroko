<?php

declare(strict_types=1);

namespace App\Cart\ApplicationService\DTO;

use App\User\Domain\Entity\User;

final readonly class ProductToCartUpdaterRequest
{
    public function __construct(public User $user, public int $productId, public int $quantity)
    {
    }
}
