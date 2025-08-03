<?php

declare(strict_types=1);

namespace App\Order\Domain\Service;

use App\Cart\Domain\Entity\Cart;
use App\Order\Domain\ValueObject\OrderTotalAmount;

/** @final */
class TotalAmountCalculator
{
    public function __invoke(Cart $cart): OrderTotalAmount
    {
        $totalAmount = OrderTotalAmount::fromZero();
        $cartItems = $cart->activeItems();

        foreach ($cartItems as $cartItem) {
            $quantity = $cartItem->quantity()->value();
            $price = $cartItem->product()->price()->toFloat();

            $totalPerProduct = $quantity * $price;

            $totalAmount = $totalAmount->increase($totalPerProduct);
        }

        return $totalAmount;
    }
}
