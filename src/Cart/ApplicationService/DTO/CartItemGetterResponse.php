<?php

declare(strict_types=1);

namespace App\Cart\ApplicationService\DTO;

use App\Cart\Domain\Entity\CartItem;

final readonly class CartItemGetterResponse
{
    private function __construct(public int $productId, public string $productName, public int $productQuantity)
    {
    }

    public static function fromCartItem(CartItem $item): self
    {
        return new self(
            productId: $item->product()->id(),
            productName: $item->product()->name()->value(),
            productQuantity: $item->quantity()->value()
        );
    }
}
