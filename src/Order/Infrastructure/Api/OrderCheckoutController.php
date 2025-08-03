<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Api;

use App\Common\Infrastructure\Framework\SymfonyApiController;
use App\Order\ApplicationService\DTO\OrderCheckoutRequest;
use App\Order\ApplicationService\OrderCheckout;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class OrderCheckoutController extends SymfonyApiController
{
    public function __construct(private readonly OrderCheckout $orderCheckout)
    {
    }

    #[OA\Post(
        path: '/api/order/checkout',
        summary: 'Checkout the current cart and generate an order.',
        tags: ["ORDER"],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Order created successfully.',
            ),
            new OA\Response(
                response: 401,
                description: 'Authorization required.',
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid input'
            )
        ]
    )]
    public function checkout(): JsonResponse
    {
        ($this->orderCheckout)(new OrderCheckoutRequest(user: $this->getUser()));

        return new JsonResponse([], Response::HTTP_CREATED);
    }
}
