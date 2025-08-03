<?php

declare(strict_types=1);

namespace App\Cart\ApplicationService;

use App\Cart\ApplicationService\DTO\ProductToCartAdderRequest;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Repository\CartRepository;
use App\Cart\Domain\Service\CartItemFactory;
use App\Cart\Domain\Service\OpenCartGetter;
use App\Product\Domain\Service\ProductFinder;

final readonly class ProductToCartAdder
{
    public function __construct(
        private CartRepository  $repository,
        private OpenCartGetter  $openCartGetter,
        private ProductFinder   $productFinder,
        private CartItemFactory $cartItemFactory
    )
    {
    }

    public function __invoke(ProductToCartAdderRequest $request): void
    {
        $cart = ($this->openCartGetter)($request->user) ?? Cart::open(user: $request->user, createdAt: $request->createdAt);
        $product = ($this->productFinder)($request->productId);

        $cartItem = ($this->cartItemFactory)(cart: $cart, product: $product, quantity: $request->quantity);

        $cart->addItem($cartItem);

        $this->repository->save($cart);
    }
}
