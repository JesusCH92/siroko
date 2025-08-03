<?php

declare(strict_types=1);

namespace App\Product\Domain\Exception;

use App\Common\Domain\Exception\CustomException;
use Throwable;

final class NotFoundProduct extends CustomException
{
    private const string NOT_FOUND_WITH_ID_MESSAGE = 'Product not found with User ID: ';

    public function __construct(int $id, int $code = 404, Throwable $previous = null)
    {
        parent::__construct(self::NOT_FOUND_WITH_ID_MESSAGE . $id, $code, $previous);
    }
}
