<?php

namespace App\Tests\Cart\CartGetter;

use App\Cart\ApplicationService\CartGetter;
use App\Cart\ApplicationService\DTO\CartGetterRequest;
use App\Cart\ApplicationService\DTO\CartGetterResponse;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Service\OpenCartFinder;
use App\Cart\Domain\ValueObject\CartStatus;
use App\User\Domain\Entity\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CartGetterTest extends TestCase
{
    private OpenCartFinder|MockObject $finder;
    private CartGetter $cartGetter;

    protected function setUp(): void
    {
        $this->finder = $this->createMock(OpenCartFinder::class);
        $this->cartGetter = new CartGetter($this->finder);
    }

    public function testShouldReturnsCartGetterResponse(): void
    {
        $user = $this->createMock(User::class);
        $cart = $this->createMock(Cart::class);
        $cart->method('status')->willReturn(CartStatus::OPEN);

        $request = new CartGetterRequest(user: $user);

        $this->finder->expects(self::once())
            ->method('__invoke')
            ->with($user)
            ->willReturn($cart);

        $response = ($this->cartGetter)($request);

        $this->assertInstanceOf(CartGetterResponse::class, $response);
        $this->assertSame($cart->status()->value, $response->status);
    }
}
