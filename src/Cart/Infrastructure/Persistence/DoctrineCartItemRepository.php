<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Persistence;

use App\Cart\Domain\Entity\CartItem;
use App\Cart\Domain\Repository\CartItemRepository;
use App\Common\Infrastructure\Persistence\DoctrineRepository;

final class DoctrineCartItemRepository extends DoctrineRepository implements CartItemRepository
{

    public function save(CartItem $cartItem): void
    {
        $this->entityManager()->persist($cartItem);
        $this->entityManager()->flush();
    }
}
