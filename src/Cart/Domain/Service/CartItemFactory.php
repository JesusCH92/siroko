<?php

declare(strict_types=1);

namespace App\Cart\Domain\Service;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartItem;
use App\Cart\Domain\ValueObject\CartItemQuantity;
use App\Product\Domain\Entity\Product;

/** @final */
class CartItemFactory
{
    public function __invoke(Cart $cart, Product $product, ?int $quantity): CartItem
    {
        $quantity = CartItemQuantity::fromInteger($quantity);
        $existingItem = $cart->matchProduct($product->id());

        $cartItem = $existingItem ?? CartItem::create(cart: $cart, product: $product, quantity: $quantity->value());

        if (null !== $existingItem) {
            $cartItem->increase(quantity: $quantity->value());
        }

        return $cartItem;
    }
}
