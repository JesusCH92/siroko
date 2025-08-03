<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use App\Common\Domain\ValueObject\Metadata;
use App\Order\Domain\ValueObject\OrderTotalAmount;
use App\User\Domain\Entity\User;
use App\User\Infrastructure\Model\SymfonyUser;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: SymfonyUser::class)]
    #[ORM\JoinColumn(name: 'user_id', nullable: false)]
    private User $user;

    #[ORM\Embedded(class: OrderTotalAmount::class, columnPrefix: false)]
    private OrderTotalAmount $totalAmount;

    #[ORM\Embedded(class: Metadata::class, columnPrefix: false)]
    private Metadata $metadata;

    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'order', cascade: ['persist'], orphanRemoval: true)]
    private Collection $items;

    private function __construct(User $user, ?float $totalAmount, ?DateTimeInterface $createdAt = null)
    {
        $this->user = $user;
        $this->totalAmount = OrderTotalAmount::fromFloat($totalAmount);
        $this->metadata = new Metadata($createdAt);
        $this->items = new ArrayCollection();
    }

    public static function create(User $user, ?float $totalAmount, ?DateTimeInterface $createdAt = null): self
    {
        return new self(user: $user, totalAmount: $totalAmount, createdAt: $createdAt);
    }

    public function metadata(): Metadata
    {
        return $this->metadata;
    }

    public function addItem(OrderItem $item): void
    {
        $this->items->add($item);
    }
}
