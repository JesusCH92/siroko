<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Api;

use App\Cart\ApplicationService\DTO\ProductToCartAdderRequest;
use App\Cart\ApplicationService\ProductToCartAdder;
use App\Common\Infrastructure\Framework\SymfonyApiController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ProductToCartAdderController extends SymfonyApiController
{
    public function __construct(private readonly ProductToCartAdder $productToCartAdder)
    {
    }

    #[OA\Post(
        path: '/api/cart/add-item',
        summary: 'Add a product to the cart.',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['productId', 'quantity'],
                properties: [
                    new OA\Property(property: 'productId', type: 'integer', example: 1),
                    new OA\Property(property: 'quantity', type: 'integer', example: 10),
                ]
            )
        ),
        tags: ["CART"],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Product added to cart',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(type: 'string'),
                    example: []
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid input'
            )
        ]
    )]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $productId = (int)($data['productId'] ?? null);
        $quantity = (int)($data['quantity'] ?? null);

        $dto = new ProductToCartAdderRequest(
            productId: $productId,
            quantity: $quantity,
            user: $this->getUser()
        );

        ($this->productToCartAdder)($dto);

        return new JsonResponse([], Response::HTTP_CREATED);
    }
}
