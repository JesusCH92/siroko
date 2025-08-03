<?php

namespace App\Cart\ApplicationService\DTO;

use App\User\Domain\Entity\User;

final readonly class CartGetterRequest
{
    public function __construct(public User $user)
    {
    }
}
