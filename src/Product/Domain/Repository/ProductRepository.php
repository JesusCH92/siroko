<?php

declare(strict_types=1);

namespace App\Product\Domain\Repository;

use App\Cart\Domain\Entity\Cart;
use App\Product\Domain\Entity\Product;

interface ProductRepository
{
    public function findById(?int $productId): ?Product;

    /** @return Product[] */
    public function findAllByCart(Cart $cart): array;
}
