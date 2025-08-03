<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Api;

use App\Cart\ApplicationService\DTO\ProductToCartUpdaterRequest;
use App\Cart\ApplicationService\ProductToCartUpdater;
use App\Common\Infrastructure\Framework\SymfonyApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;

final class ProductToCartUpdaterController extends SymfonyApiController
{
    public function __construct(private ProductToCartUpdater $productToCartUpdater)
    {
    }

    #[OA\Patch(
        path: '/api/cart/update-item',
        summary: 'Update quantity a product to the cart.',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['productId', 'quantity'],
                properties: [
                    new OA\Property(property: 'productId', type: 'integer', example: 2),
                    new OA\Property(property: 'quantity', type: 'integer', example: 11),
                ]
            )
        ),
        tags: ["CART"],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Product quantity updated to cart',
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
    public function update(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $productId = (int)($data['productId'] ?? null);
        $quantity = (int)($data['quantity'] ?? 0);

        $dto = new ProductToCartUpdaterRequest(user: $this->getUser(), productId: $productId, quantity: $quantity);

        ($this->productToCartUpdater)($dto);

        return new JsonResponse([], Response::HTTP_OK);
    }
}
