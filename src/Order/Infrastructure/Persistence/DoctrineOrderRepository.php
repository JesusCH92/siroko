<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Persistence;

use App\Common\Infrastructure\Persistence\DoctrineRepository;
use App\Order\Domain\Entity\Order;
use App\Order\Domain\Repository\OrderRepository;

final class DoctrineOrderRepository extends DoctrineRepository implements OrderRepository
{
    public function save(Order $order): void
    {
        $this->entityManager()->persist($order);
        $this->entityManager()->flush();
    }
}
