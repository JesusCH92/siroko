<?php

declare(strict_types=1);

namespace App\Product\Domain\Exception;

use App\Common\Domain\Exception\CustomException;
use Throwable;

final class InvalidName extends CustomException
{
    public function __construct(string $message, int $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
