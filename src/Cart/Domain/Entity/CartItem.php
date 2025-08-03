<?php

declare(strict_types=1);

namespace App\Cart\Domain\Entity;

use App\Cart\Domain\ValueObject\CartItemQuantity;
use App\Common\Domain\ValueObject\Metadata;
use App\Product\Domain\Entity\Product;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'cart_item')]
class CartItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\ManyToOne(targetEntity: Cart::class, inversedBy: 'items')]
    #[ORM\JoinColumn(name: 'cart_id', nullable: false)]
    private Cart $cart;
    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(name: 'product_id', nullable: false)]
    private Product $product;
    #[ORM\Embedded(class: CartItemQuantity::class, columnPrefix: false)]
    private CartItemQuantity $quantity;
    #[ORM\Embedded(class: Metadata::class, columnPrefix: false)]
    private Metadata $metadata;

    private function __construct(Cart $cart, Product $product, ?int $quantity, ?DateTimeInterface $createdAt = null)
    {
        $this->cart = $cart;
        $this->product = $product;
        $this->quantity = CartItemQuantity::fromInteger($quantity);
        $this->metadata = new Metadata(createdAt: $createdAt);
    }

    public static function create(
        Cart               $cart,
        Product            $product,
        ?int               $quantity,
        ?DateTimeInterface $createdAt = null
    ): self
    {
        return new self(cart: $cart, product: $product, quantity: $quantity, createdAt: $createdAt);
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function cart(): Cart
    {
        return $this->cart;
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function quantity(): CartItemQuantity
    {
        return $this->quantity;
    }

    public function metadata(): Metadata
    {
        return $this->metadata;
    }

    public function increase(int $quantity): void
    {
        $this->quantity = $this->quantity->increase($quantity);
    }

    public function modifyQuantity(int $quantity): void
    {
        $this->quantity = $this->quantity->modify($quantity);
    }
}
