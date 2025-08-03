<?php

namespace App\Tests\Cart\ProductToCartUpdater;

use App\Cart\ApplicationService\DTO\ProductToCartUpdaterRequest;
use App\Cart\ApplicationService\ProductToCartUpdater;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartItem;
use App\Cart\Domain\Repository\CartItemRepository;
use App\Cart\Domain\Service\CartItemFinder;
use App\Cart\Domain\Service\OpenCartFinder;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Service\ProductFinder;
use App\User\Domain\Entity\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductToCartUpdaterTest extends TestCase
{
    private OpenCartFinder|MockObject $openCartFinder;
    private ProductFinder|MockObject $productFinder;
    private CartItemFinder|MockObject $cartItemFinder;
    private CartItemRepository|MockObject $cartItemRepository;

    private ProductToCartUpdater $updater;

    protected function setUp(): void
    {
        $this->openCartFinder = $this->createMock(OpenCartFinder::class);
        $this->productFinder = $this->createMock(ProductFinder::class);
        $this->cartItemFinder = $this->createMock(CartItemFinder::class);
        $this->cartItemRepository = $this->createMock(CartItemRepository::class);

        $this->updater = new ProductToCartUpdater(
            $this->openCartFinder,
            $this->productFinder,
            $this->cartItemFinder,
            $this->cartItemRepository
        );
    }

    public function testShouldUpdatesQuantity(): void
    {
        $user = $this->createMock(User::class);
        $product = $this->createMock(Product::class);
        $cart = $this->createMock(Cart::class);
        $cartItem = $this->createMock(CartItem::class);

        $request = new ProductToCartUpdaterRequest(user: $user, productId: 789, quantity: 5);

        $this->openCartFinder
            ->expects(self::once())
            ->method('__invoke')
            ->with($user)
            ->willReturn($cart);

        $this->productFinder
            ->expects(self::once())
            ->method('__invoke')
            ->with(789)
            ->willReturn($product);

        $product->method('id')->willReturn(789);

        $this->cartItemFinder
            ->expects(self::once())
            ->method('__invoke')
            ->with($cart, 789)
            ->willReturn($cartItem);

        $cartItem
            ->expects(self::once())
            ->method('modifyQuantity')
            ->with(5);

        $this->cartItemRepository
            ->expects(self::once())
            ->method('save')
            ->with($cartItem);

        ($this->updater)($request);
    }
}
