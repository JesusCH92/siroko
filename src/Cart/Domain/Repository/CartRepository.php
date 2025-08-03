<?php

declare(strict_types=1);

namespace App\Cart\Domain\Repository;

use App\Cart\Domain\Entity\Cart;
use App\User\Domain\Entity\User;

interface CartRepository
{
    public function findOpenCartByUser(User $user): ?Cart;

    public function save(Cart $cart): void;
}
