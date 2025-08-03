<?php

namespace App\Tests\Cart\Service;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartItem;
use App\Cart\Domain\Exception\NotFoundCartItem;
use App\Cart\Domain\Service\CartItemFinder;
use PHPUnit\Framework\TestCase;

class CartItemFinderTest extends TestCase
{
    private CartItemFinder $finder;

    protected function setUp(): void
    {
        $this->finder = new CartItemFinder();
    }

    public function testShouldFindCartItem(): void
    {
        $cart = $this->createMock(Cart::class);
        $productId = 1_000;
        $cartItem = $this->createMock(CartItem::class);

        $cart->expects(self::once())
            ->method('matchProduct')
            ->with($productId)
            ->willReturn($cartItem);

        $result = ($this->finder)($cart, $productId);

        $this->assertSame($cartItem, $result);
    }

    public function testShouldThrowExceptionIfItemNotFound(): void
    {
        $cart = $this->createMock(Cart::class);
        $productId = 1_000;

        $cart->expects(self::once())
            ->method('matchProduct')
            ->with($productId)
            ->willReturn(null);

        $this->expectException(NotFoundCartItem::class);

        ($this->finder)($cart, $productId);
    }
}
