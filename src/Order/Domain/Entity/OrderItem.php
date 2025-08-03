<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use App\Cart\Domain\Entity\CartItem;
use App\Common\Domain\ValueObject\Metadata;
use App\Order\Domain\ValueObject\OrderItemPrice;
use App\Order\Domain\ValueObject\OrderItemQuantity;
use App\Product\Domain\Entity\Product;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_item')]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'items')]
    #[ORM\JoinColumn(name: 'order_id', nullable: false)]
    private Order $order;
    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(name: 'product_id', nullable: false)]
    private Product $product;
    #[ORM\Embedded(class: OrderItemQuantity::class, columnPrefix: false)]
    private OrderItemQuantity $quantity;
    #[ORM\Embedded(class: OrderItemPrice::class, columnPrefix: false)]
    private OrderItemPrice $price;
    #[ORM\Embedded(class: Metadata::class, columnPrefix: false)]
    private Metadata $metadata;

    private function __construct(
        Order              $order,
        Product            $product,
        ?int               $quantity,
        ?float             $price,
        ?DateTimeInterface $createdAt = null
    )
    {
        $this->order = $order;
        $this->product = $product;
        $this->quantity = OrderItemQuantity::fromInteger($quantity);
        $this->price = OrderItemPrice::fromFloat($price);
        $this->metadata = new Metadata($createdAt);
    }

    public static function fromCartItem(Order $order, CartItem $item): self
    {
        return new self(
            order: $order,
            product: $item->product(),
            quantity: $item->quantity()->value(),
            price: $item->quantity()->value(),
            createdAt: $order->metadata()->createdAt()
        );
    }
}
