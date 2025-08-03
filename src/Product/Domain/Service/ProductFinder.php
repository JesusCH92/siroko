<?php

declare(strict_types=1);

namespace App\Product\Domain\Service;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Exception\NotFoundProduct;
use App\Product\Domain\Repository\ProductRepository;

/** @final */
class ProductFinder
{
    public function __construct(private readonly ProductRepository $repository)
    {
    }

    public function __invoke(?int $productId): Product
    {
        $product = $this->repository->findById($productId);

        if (null === $product) {
            throw new NotFoundProduct($productId);
        }

        return $product;
    }
}
