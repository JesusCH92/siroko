<?php

declare(strict_types=1);

namespace App\Order\ApplicationService;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Exception\EmptyCart;
use App\Cart\Domain\Repository\CartRepository;
use App\Cart\Domain\Service\OpenCartFinder;
use App\Common\Domain\TransactionManager;
use App\Order\ApplicationService\DTO\OrderCheckoutRequest;
use App\Order\Domain\Entity\Order;
use App\Order\Domain\Entity\OrderItem;
use App\Order\Domain\Repository\OrderRepository;
use App\Order\Domain\Service\TotalAmountCalculator;

final readonly class OrderCheckout
{
    public function __construct(
        private OpenCartFinder        $openCartFinder,
        private TotalAmountCalculator $totalAmountCalculator,
        private OrderRepository       $repository,
        private CartRepository        $cartRepository,
        private TransactionManager    $transactionManager
    )
    {
    }

    public function __invoke(OrderCheckoutRequest $request): void
    {
        $cart = ($this->openCartFinder)($request->user);

        $this->checkIfCartIsNotEmpty($cart);

        $cartItems = $cart->activeItems();
        $totalAmount = ($this->totalAmountCalculator)($cart);

        $order = Order::create(user: $request->user, totalAmount: $totalAmount->toFloat());

        foreach ($cartItems as $cartItem) {
            $orderItem = OrderItem::fromCartItem($order, $cartItem);

            $order->addItem($orderItem);
        }

        $cart->completed();

        $this->transactionManager->run(function () use ($order, $cart) {
            $this->repository->save($order);
            $this->cartRepository->save($cart);
        });
    }

    private function checkIfCartIsNotEmpty(Cart $cart): void
    {
        if ($cart->isEmpty()) {
            throw new EmptyCart();
        }
    }
}
