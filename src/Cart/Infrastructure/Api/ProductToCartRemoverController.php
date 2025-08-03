<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Api;

use App\Cart\ApplicationService\DTO\ProductToCartRemoverRequest;
use App\Cart\ApplicationService\ProductToCartRemover;
use App\Common\Infrastructure\Framework\SymfonyApiController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ProductToCartRemoverController extends SymfonyApiController
{
    public function __construct(private readonly ProductToCartRemover $productToCartRemover)
    {
    }

    #[OA\Delete(
        path: '/api/cart/remove-item',
        summary: 'Remove a product to the cart.',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['productId'],
                properties: [
                    new OA\Property(property: 'productId', type: 'integer', example: 2),
                ]
            )
        ),
        tags: ["CART"],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Product removed to cart',
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
    public function remove(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $productId = (int)($data['productId'] ?? null);

        $dto = new ProductToCartRemoverRequest(user: $this->getUser(), productId: $productId);

        ($this->productToCartRemover)($dto);

        return new JsonResponse([], Response::HTTP_OK);
    }
}
