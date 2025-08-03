<?php

declare(strict_types=1);

namespace App\Cart\ApplicationService;

use App\Cart\ApplicationService\DTO\CartGetterRequest;
use App\Cart\ApplicationService\DTO\CartGetterResponse;
use App\Cart\Domain\Service\OpenCartFinder;

final readonly class CartGetter
{
    public function __construct(private OpenCartFinder $finder)
    {
    }

    public function __invoke(CartGetterRequest $request): CartGetterResponse
    {
        $cart = ($this->finder)($request->user);

        return CartGetterResponse::fromCart($cart);
    }
}
