<?php

namespace App\Tests\Order\OrderCheckout;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartItem;
use App\Cart\Domain\Repository\CartRepository;
use App\Cart\Domain\Service\OpenCartFinder;
use App\Common\Domain\TransactionManager;
use App\Order\ApplicationService\DTO\OrderCheckoutRequest;
use App\Order\ApplicationService\OrderCheckout;
use App\Order\Domain\Entity\Order;
use App\Order\Domain\Repository\OrderRepository;
use App\Order\Domain\Service\TotalAmountCalculator;
use App\Order\Domain\ValueObject\OrderTotalAmount;
use App\Product\Domain\Entity\Product;
use App\User\Domain\Entity\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class OrderCheckoutTest extends TestCase
{
    private OpenCartFinder|MockObject $cartFinder;
    private TotalAmountCalculator|MockObject $calculator;
    private OrderRepository|MockObject $orderRepository;
    private CartRepository|MockObject $cartRepository;
    private TransactionManager|MockObject $transactionManager;

    private OrderCheckout $orderCheckout;

    protected function setUp(): void
    {
        $this->cartFinder = $this->createMock(OpenCartFinder::class);
        $this->calculator = $this->createMock(TotalAmountCalculator::class);
        $this->orderRepository = $this->createMock(OrderRepository::class);
        $this->cartRepository = $this->createMock(CartRepository::class);
        $this->transactionManager = $this->createMock(TransactionManager::class);

        $this->orderCheckout = new OrderCheckout(
            $this->cartFinder,
            $this->calculator,
            $this->orderRepository,
            $this->cartRepository,
            $this->transactionManager
        );
    }

    public function testShouldCheckout(): void
    {
        $user = $this->createMock(User::class);
        $request = new OrderCheckoutRequest($user);

        $cart = $this->createMock(Cart::class);
        $cartItem = CartItem::create(
            cart: $cart,
            product: Product::create(name: 'Test', price: 10.0),
            quantity: 2
        );

        $this->cartFinder
            ->expects(self::once())
            ->method('__invoke')
            ->with($user)
            ->willReturn($cart);

        $cart
            ->method('isEmpty')
            ->willReturn(false);

        $cart
            ->method('activeItems')
            ->willReturn([$cartItem]);

        $this->calculator
            ->expects(self::once())
            ->method('__invoke')
            ->with($cart)
            ->willReturnCallback(fn() => OrderTotalAmount::fromFloat(20.0));

        $cart
            ->expects(self::once())
            ->method('completed');

        $this->transactionManager
            ->expects(self::once())
            ->method('run')
            ->with($this->callback(function (callable $callback) use ($cart): bool {
                $orderRepository = $this->orderRepository;
                $cartRepository = $this->cartRepository;

                $orderRepository
                    ->expects(self::once())
                    ->method('save')
                    ->with($this->isInstanceOf(Order::class));
                $cartRepository->expects(self::once())->method('save')->with($cart);

                $callback();

                return true;
            }));

        ($this->orderCheckout)($request);
    }
}
