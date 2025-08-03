<?php

declare(strict_types=1);

namespace App\Cart\ApplicationService;

use App\Cart\ApplicationService\DTO\ProductToCartUpdaterRequest;
use App\Cart\Domain\Repository\CartItemRepository;
use App\Cart\Domain\Service\CartItemFinder;
use App\Cart\Domain\Service\OpenCartFinder;
use App\Cart\Domain\ValueObject\CartItemQuantity;
use App\Product\Domain\Service\ProductFinder;

final readonly class ProductToCartUpdater
{
    public function __construct(
        private OpenCartFinder     $openCartFinder,
        private ProductFinder      $productFinder,
        private CartItemFinder     $cartItemFinder,
        private CartItemRepository $cartItemRepository
    )
    {
    }

    public function __invoke(ProductToCartUpdaterRequest $request): void
    {
        $cart = ($this->openCartFinder)($request->user);
        $product = ($this->productFinder)($request->productId);
        $quantity = CartItemQuantity::fromInteger($request->quantity);

        $cartItem = ($this->cartItemFinder)($cart, $product->id());

        $cartItem->modifyQuantity(quantity: $quantity->value());

        $this->cartItemRepository->save($cartItem);
    }
}
