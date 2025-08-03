<?php

namespace App\Tests\Cart\Service;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Exception\NotFoundCart;
use App\Cart\Domain\Repository\CartRepository;
use App\Cart\Domain\Service\OpenCartFinder;
use App\User\Domain\Entity\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class OpenCartFinderTest extends TestCase
{
    private CartRepository|MockObject $repository;
    private OpenCartFinder $finder;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(CartRepository::class);
        $this->finder = new OpenCartFinder($this->repository);
    }

    public function testShouldFindOpenCart(): void
    {
        $user = $this->createMock(User::class);
        $cart = $this->createMock(Cart::class);

        $this->repository
            ->expects(self::once())
            ->method('findOpenCartByUser')
            ->with($user)
            ->willReturn($cart);

        $result = ($this->finder)($user);

        $this->assertSame($cart, $result);
    }

    public function testShouldThrowsExceptionIfCartNotFound(): void
    {
        $user = $this->createMock(User::class);
        $user->method('id')->willReturn(42);

        $this->repository
            ->expects(self::once())
            ->method('findOpenCartByUser')
            ->with($user)
            ->willReturn(null);

        $this->expectException(NotFoundCart::class);

        ($this->finder)($user);
    }
}
