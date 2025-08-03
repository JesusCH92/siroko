<?php

namespace App\Tests\Cart\Service;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Repository\CartRepository;
use App\Cart\Domain\Service\OpenCartGetter;
use App\User\Domain\Entity\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class OpenCartGetterTest extends TestCase
{
    private CartRepository|MockObject $repository;
    private OpenCartGetter $getter;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(CartRepository::class);
        $this->getter = new OpenCartGetter($this->repository);
    }

    public function testShouldReturnCartIfFound(): void
    {
        $user = $this->createMock(User::class);
        $cart = $this->createMock(Cart::class);

        $this->repository->expects(self::once())
            ->method('findOpenCartByUser')
            ->with($user)
            ->willReturn($cart);

        $result = ($this->getter)($user);

        $this->assertSame($cart, $result);
    }

    public function testShouldReturnNullIfCartNotFound(): void
    {
        $user = $this->createMock(User::class);

        $this->repository->expects(self::once())
            ->method('findOpenCartByUser')
            ->with($user)
            ->willReturn(null);

        $result = ($this->getter)($user);

        $this->assertNull($result);
    }
}
