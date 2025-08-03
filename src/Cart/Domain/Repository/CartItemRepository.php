<?php

declare(strict_types=1);

namespace App\Cart\Domain\Repository;

use App\Cart\Domain\Entity\CartItem;

interface CartItemRepository
{
    public function save(CartItem $cartItem): void;
}
