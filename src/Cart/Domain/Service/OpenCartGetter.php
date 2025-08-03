<?php

declare(strict_types=1);

namespace App\Cart\Domain\Service;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Repository\CartRepository;
use App\User\Domain\Entity\User;

/** @final */
class OpenCartGetter
{
    public function __construct(private readonly CartRepository $repository)
    {
    }

    public function __invoke(User $user): ?Cart
    {
        return $this->repository->findOpenCartByUser($user);
    }
}
