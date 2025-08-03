<?php

declare(strict_types=1);

namespace App\Cart\Domain\Entity;

use App\Cart\Domain\ValueObject\CartStatus;
use App\Common\Domain\ValueObject\Metadata;
use App\User\Domain\Entity\User;
use App\User\Infrastructure\Model\SymfonyUser;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'cart')]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: SymfonyUser::class)]
    #[ORM\JoinColumn(name: 'user_id', nullable: false)]
    private User $user;

    #[ORM\Column(enumType: CartStatus::class)]
    private CartStatus $status;

    #[ORM\Embedded(class: Metadata::class, columnPrefix: false)]
    private Metadata $metadata;

    #[ORM\OneToMany(targetEntity: CartItem::class, mappedBy: 'cart', cascade: ['persist'], orphanRemoval: true)]
    private Collection $items;

    private function __construct(User $user, ?string $status, ?DateTimeInterface $createdAt = null)
    {
        $this->user = $user;
        $this->status = CartStatus::fromStatus($status);
        $this->items = new ArrayCollection();
        $this->metadata = new Metadata(createdAt: $createdAt);
    }

    public static function open(User $user, ?DateTimeInterface $createdAt = null): self
    {
        return new self(user: $user, status: CartStatus::OPEN->value, createdAt: $createdAt);
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function status(): CartStatus
    {
        return $this->status;
    }

    public function metadata(): Metadata
    {
        return $this->metadata;
    }

    /** @return CartItem[] */
    public function activeItems(): array
    {
        return $this->items->filter(fn(CartItem $item) => $item->metadata()->deletedAt() === null)->toArray();
    }

    public function matchProduct(int $productId): ?CartItem
    {
        foreach ($this->activeItems() as $item) {
            if ($item->product()->id() === $productId) {
                return $item;
            }
        }

        return null;
    }

    public function addItem(CartItem $item): void
    {
        $this->items->add($item);
    }

    public function isEmpty(): bool
    {
        return $this->activeItems() === [];
    }

    public function completed(): void
    {
        $this->status = $this->status->completed();
    }
}
