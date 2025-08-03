<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Persistence;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartItem;
use App\Common\Infrastructure\Persistence\DoctrineRepository;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductRepository;

final class DoctrineProductRepository extends DoctrineRepository implements ProductRepository
{
    public function findById(?int $productId): ?Product
    {
        return $this->repository(Product::class)
            ->findOneBy(['id' => $productId, 'metadata.deletedAt' => null]);
    }

    /** @return Product[] */
    public function findAllByCart(Cart $cart): array
    {
        $qb = $this->ormQueryBuilder()
            ->select('p')
            ->from(Product::class, 'd')
            ->innerJoin(
                CartItem::class,
                'ci',
                'WITH',
                'ci.product = p.id AND ci.cart = :cart AND t.metadata.deleteAt IS NULL'
            )
            ->setParameter('cart', $cart);

        return $qb->getQuery()->getResult();
    }
}
