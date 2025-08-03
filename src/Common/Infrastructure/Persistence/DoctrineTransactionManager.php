<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Persistence;

use App\Common\Domain\TransactionManager;
use Throwable;

final class DoctrineTransactionManager extends DoctrineRepository implements TransactionManager
{
    public function run(callable $operation): void
    {
        $connection = $this->entityManager()->getConnection();

        $connection->beginTransaction();

        try {
            $operation();
            $connection->commit();
        } catch (Throwable $e) {
            $connection->rollBack();
            throw $e;
        }
    }
}
