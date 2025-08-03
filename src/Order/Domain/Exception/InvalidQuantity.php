<?php

declare(strict_types=1);

namespace App\Order\Domain\Exception;

use App\Common\Domain\Exception\CustomException;

final class InvalidQuantity extends CustomException
{
    public function __construct(string $message, int $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
