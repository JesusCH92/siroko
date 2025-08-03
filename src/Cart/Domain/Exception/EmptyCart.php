<?php

declare(strict_types=1);

namespace App\Cart\Domain\Exception;

use App\Common\Domain\Exception\CustomException;
use Throwable;

final class EmptyCart extends CustomException
{
    private const string EMPTY_CART_MESSAGE = 'Cart must not be empty.';

    public function __construct(int $code = 400, Throwable $previous = null)
    {
        parent::__construct(self::EMPTY_CART_MESSAGE, $code, $previous);
    }
}
