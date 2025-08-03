<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Persistence;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Repository\CartRepository;
use App\Cart\Domain\ValueObject\CartStatus;
use App\Common\Infrastructure\Persistence\DoctrineRepository;
use App\User\Domain\Entity\User;

final class DoctrineCartRepository extends DoctrineRepository implements CartRepository
{
    public function findOpenCartByUser(User $user): ?Cart
    {
        return $this
            ->repository(Cart::class)
            ->findOneBy(
                [
                    'user' => $user,
                    'status' => CartStatus::OPEN
                ]
            );
    }

    public function save(Cart $cart): void
    {
        $this->entityManager()->persist($cart);
        $this->entityManager()->flush();
    }
}
