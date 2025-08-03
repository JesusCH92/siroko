<?php

declare(strict_types=1);

namespace App\Cart\ApplicationService\DTO;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartItem;

final readonly class CartGetterResponse
{
    public array $items;

    private function __construct(
        public ?int            $id,
        public string          $status,
        public string          $createdAt,
        CartItemGetterResponse ...$cartItems
    )
    {
        $this->items = $cartItems;
    }

    public static function fromCart(Cart $cart): self
    {
        $items = array_map(fn(CartItem $item) => CartItemGetterResponse::fromCartItem($item), $cart->activeItems());

        return new self(
            $cart->id(),
            $cart->status()->value,
            $cart->metadata()->createdAt()->format('Y-m-d H-i-s'),
            ...$items
        );
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'createdAt' => $this->createdAt,
            'items' => $this->items,
        ];
    }
}
