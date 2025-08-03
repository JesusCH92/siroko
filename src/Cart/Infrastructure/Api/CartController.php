<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Api;

use App\Cart\ApplicationService\CartGetter;
use App\Cart\ApplicationService\DTO\CartGetterRequest;
use App\Common\Infrastructure\Framework\SymfonyApiController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CartController extends SymfonyApiController
{
    public function __construct(private CartGetter $getter)
    {
    }

    #[OA\Get(
        path: "/api/cart",
        summary: "get the products from the shopping cart.",
        tags: ["CART"],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Cart retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 2),
                        new OA\Property(property: 'status', type: 'string', example: 'open'),
                        new OA\Property(property: 'createdAt', type: 'string', format: 'date-time', example: '2025-08-03 00-00-00'),
                        new OA\Property(
                            property: 'items',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'productId', type: 'integer', example: 1),
                                    new OA\Property(property: 'productName', type: 'string', example: 'product 1'),
                                    new OA\Property(property: 'productQuantity', type: 'integer', example: 13),
                                ],
                                type: 'object'
                            )
                        )
                    ],
                    type: 'object'
                )
            )
        ]
    )]
    public function cart(): JsonResponse
    {
        $cartResponse = ($this->getter)(new CartGetterRequest($this->getUser()));

        return new JsonResponse($cartResponse->serialize(), Response::HTTP_OK);
    }
}
