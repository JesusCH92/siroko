<?php

namespace App\Tests\Order\Service;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartItem;
use App\Order\Domain\Service\TotalAmountCalculator;
use App\Order\Domain\ValueObject\OrderTotalAmount;
use App\Product\Domain\Entity\Product;
use PHPUnit\Framework\TestCase;

class TotalAmountCalculatorTest extends TestCase
{
    private TotalAmountCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new TotalAmountCalculator();
    }

    public function testShouldCalculatesTotalCorrectly(): void
    {

        $productA = Product::create(name: 'Product A', price: 10.00);
        $productB = Product::create(name: 'Product B', price: 5.5);

        $cart = $this->createMock(Cart::class);

        $itemA = CartItem::create(cart: $cart, product: $productA, quantity: 2);
        $itemB = CartItem::create(cart: $cart, product: $productB, quantity: 3);


        $cart->method('activeItems')->willReturn([$itemA, $itemB]);

        $total = ($this->calculator)($cart);

        $this->assertInstanceOf(OrderTotalAmount::class, $total);
        $this->assertEqualsWithDelta(36.5, $total->toFloat(), 0.001);
    }
}
