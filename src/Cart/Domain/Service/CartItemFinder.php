<?php

declare(strict_types=1);

namespace App\Cart\Domain\Service;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartItem;
use App\Cart\Domain\Exception\NotFoundCartItem;

/** @final */
class CartItemFinder
{
    public function __invoke(Cart $cart, int $productId): CartItem
    {
        $cartItem = $cart->matchProduct($productId);

        if (null === $cartItem) {
            throw new NotFoundCartItem($productId);
        }

        return $cartItem;
    }
}
