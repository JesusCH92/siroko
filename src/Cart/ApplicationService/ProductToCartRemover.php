<?php

declare(strict_types=1);

namespace App\Cart\ApplicationService;

use App\Cart\ApplicationService\DTO\ProductToCartRemoverRequest;
use App\Cart\Domain\Repository\CartItemRepository;
use App\Cart\Domain\Service\CartItemFinder;
use App\Cart\Domain\Service\OpenCartFinder;
use App\Product\Domain\Service\ProductFinder;

final readonly class ProductToCartRemover
{
    public function __construct(
        private OpenCartFinder     $openCartFinder,
        private ProductFinder      $productFinder,
        private CartItemFinder     $cartItemFinder,
        private CartItemRepository $cartItemRepository
    )
    {
    }

    public function __invoke(ProductToCartRemoverRequest $request): void
    {
        $cart = ($this->openCartFinder)($request->user);
        $product = ($this->productFinder)($request->productId);

        $cartItem = ($this->cartItemFinder)($cart, $product->id());

        $cartItem->metadata()->deleting();

        $this->cartItemRepository->save($cartItem);
    }
}
