<?php

declare(strict_types=1);

namespace App\Common\Domain\ValueObject;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Metadata
{
    #[ORM\Column(name: 'created_at', type: Types::DATE_IMMUTABLE, nullable: false)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(name: 'deleted_at', type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $deletedAt = null;

    public function __construct(?DateTimeInterface $createdAt = null)
    {
        $this->createdAt = $createdAt ?? new DateTimeImmutable();
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function deletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function deleting(?DateTimeInterface $deletedAt = null): void
    {
        $this->deletedAt = $deletedAt ?? new DateTimeImmutable();
    }
}
