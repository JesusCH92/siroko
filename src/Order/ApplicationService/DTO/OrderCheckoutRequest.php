<?php

declare(strict_types=1);

namespace App\Order\ApplicationService\DTO;

use App\User\Domain\Entity\User;

final readonly class OrderCheckoutRequest
{
    public function __construct(public User $user)
    {
    }
}
