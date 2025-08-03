<?php

declare(strict_types=1);

namespace App\User\Domain\Entity;

use App\Common\Domain\ValueObject\Metadata;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Password;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Embedded(class: Email::class, columnPrefix: false)]
    protected Email $email;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    protected array $roles = [];

    #[ORM\Embedded(class: Password::class, columnPrefix: false)]
    protected Password $password;

    #[ORM\Embedded(class: Metadata::class, columnPrefix: false)]
    protected Metadata $metadata;

    private function __construct(?string $email, ?string $plainHashedPassword, ?DateTimeInterface $createdAt = null)
    {
        $this->email = Email::fromString($email);
        $this->password = Password::fromString($plainHashedPassword);
        $this->metadata = new Metadata($createdAt);
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): Password
    {
        return $this->password;
    }
}
