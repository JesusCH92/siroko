<?php

namespace App\Tests\Cart\Service;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartItem;
use App\Cart\Domain\Service\CartItemFactory;
use App\Product\Domain\Entity\Product;
use PHPUnit\Framework\TestCase;

class CartItemFactoryTest extends TestCase
{
    private CartItemFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new CartItemFactory();
    }

    public function testShouldCreateNewCartItemWhenNotExists(): void
    {
        $cart = $this->createMock(Cart::class);
        $product = $this->createMock(Product::class);
        $productId = 1_000;

        $product->method('id')->willReturn($productId);

        $cart->method('matchProduct')
            ->with($productId)
            ->willReturn(null);

        $result = ($this->factory)($cart, $product, 3);

        $this->assertInstanceOf(CartItem::class, $result);
    }

    public function testShouldIncreaseQuantityWhenItemExists(): void
    {
        $cart = $this->createMock(Cart::class);
        $product = $this->createMock(Product::class);
        $productId = 5_000;
        $existingItem = $this->createMock(CartItem::class);

        $product->method('id')->willReturn($productId);

        $cart->method('matchProduct')
            ->with($productId)
            ->willReturn($existingItem);

        $existingItem->expects(self::once())
            ->method('increase')
            ->with(2);

        $result = ($this->factory)($cart, $product, 2);

        $this->assertSame($existingItem, $result);
    }
}
