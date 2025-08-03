<?php

declare(strict_types=1);

namespace App\Common\Domain;

interface TransactionManager
{
    public function run(callable $operation): void;
}
