<?php

namespace App\Tests\Cart\ProductToCartRemover;

use App\Cart\ApplicationService\DTO\ProductToCartRemoverRequest;
use App\Cart\ApplicationService\ProductToCartRemover;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartItem;
use App\Cart\Domain\Repository\CartItemRepository;
use App\Cart\Domain\Service\CartItemFinder;
use App\Cart\Domain\Service\OpenCartFinder;
use App\Common\Domain\ValueObject\Metadata;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Service\ProductFinder;
use App\User\Domain\Entity\User;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductToCartRemoverTest extends TestCase
{
    private OpenCartFinder|MockObject $openCartFinder;
    private ProductFinder|MockObject $productFinder;
    private CartItemFinder|MockObject $cartItemFinder;
    private CartItemRepository|MockObject $cartItemRepository;

    private ProductToCartRemover $remover;

    protected function setUp(): void
    {
        $this->openCartFinder = $this->createMock(OpenCartFinder::class);
        $this->productFinder = $this->createMock(ProductFinder::class);
        $this->cartItemFinder = $this->createMock(CartItemFinder::class);
        $this->cartItemRepository = $this->createMock(CartItemRepository::class);

        $this->remover = new ProductToCartRemover(
            $this->openCartFinder,
            $this->productFinder,
            $this->cartItemFinder,
            $this->cartItemRepository
        );
    }

    public function testShouldDeletesCartItem(): void
    {
        $user = $this->createMock(User::class);
        $product = $this->createMock(Product::class);
        $cart = $this->createMock(Cart::class);
        $cartItem = $this->createMock(CartItem::class);

        $metadata = new Metadata(new DateTimeImmutable('2025-08-03 12:00:00'));

        $cartItem->method('metadata')->willReturn($metadata);

        $request = new ProductToCartRemoverRequest(user: $user, productId: 123);

        $this->openCartFinder
            ->expects(self::once())
            ->method('__invoke')
            ->with($user)
            ->willReturn($cart);

        $this->productFinder
            ->expects(self::once())
            ->method('__invoke')
            ->with(123)
            ->willReturn($product);

        $product->method('id')->willReturn(123);

        $this->cartItemFinder
            ->expects(self::once())
            ->method('__invoke')
            ->with($cart, 123)
            ->willReturn($cartItem);

        $this->cartItemRepository
            ->expects(self::once())
            ->method('save')
            ->with($cartItem);

        ($this->remover)($request);

        $this->assertNotNull($metadata->deletedAt(), 'if it is not null, it is eliminated');
    }
}
