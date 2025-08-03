<?php

namespace App\Tests\Cart\ProductToCartAdder;

use App\Cart\ApplicationService\DTO\ProductToCartAdderRequest;
use App\Cart\ApplicationService\ProductToCartAdder;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartItem;
use App\Cart\Domain\Repository\CartRepository;
use App\Cart\Domain\Service\CartItemFactory;
use App\Cart\Domain\Service\OpenCartGetter;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Service\ProductFinder;
use App\User\Domain\Entity\User;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductToCartAdderTest extends TestCase
{
    private CartRepository|MockObject $repository;
    private OpenCartGetter|MockObject $openCartGetter;
    private ProductFinder|MockObject $productFinder;
    private CartItemFactory|MockObject $cartItemFactory;

    private ProductToCartAdder $adder;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(CartRepository::class);
        $this->openCartGetter = $this->createMock(OpenCartGetter::class);
        $this->productFinder = $this->createMock(ProductFinder::class);
        $this->cartItemFactory = $this->createMock(CartItemFactory::class);

        $this->adder = new ProductToCartAdder(
            $this->repository,
            $this->openCartGetter,
            $this->productFinder,
            $this->cartItemFactory
        );
    }

    public function testShouldAddsProductToExistingCart(): void
    {
        $user = $this->createMock(User::class);
        $product = $this->createMock(Product::class);
        $cart = $this->createMock(Cart::class);
        $cartItem = $this->createMock(CartItem::class);

        $request = new ProductToCartAdderRequest(
            productId: 123,
            quantity: 5,
            user: $user
        );

        $this->openCartGetter
            ->expects(self::once())
            ->method('__invoke')
            ->with($user)
            ->willReturn($cart);

        $this->productFinder
            ->expects(self::once())
            ->method('__invoke')
            ->with(123)
            ->willReturn($product);

        $this->cartItemFactory
            ->expects(self::once())
            ->method('__invoke')
            ->with($cart, $product, 5)
            ->willReturn($cartItem);

        $cart->expects(self::once())
            ->method('addItem')
            ->with($cartItem);

        $this->repository
            ->expects(self::once())
            ->method('save')
            ->with($cart);

        ($this->adder)($request);
    }

    public function testShouldCreatesNewCartIfNoneExists(): void
    {
        $currentTime = new DateTimeImmutable();

        $user = $this->createMock(User::class);
        $product = $this->createMock(Product::class);
        $cartItem = $this->createMock(CartItem::class);

        $request = new ProductToCartAdderRequest(
            productId: 456,
            quantity: 2,
            user: $user,
            createdAt: $currentTime
        );

        $this->openCartGetter
            ->expects(self::once())
            ->method('__invoke')
            ->with($user)
            ->willReturn(null);

        $realCart = Cart::open(user: $user, createdAt: $currentTime);

        $this->productFinder
            ->expects(self::once())
            ->method('__invoke')
            ->with(456)
            ->willReturn($product);

        $this->cartItemFactory
            ->expects(self::once())
            ->method('__invoke')
            ->with($realCart, $product, 2)
            ->willReturn($cartItem);

        $this->repository
            ->expects(self::once())
            ->method('save')
            ->with(self::isInstanceOf(Cart::class));

        ($this->adder)($request);
    }
}
