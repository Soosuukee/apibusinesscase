<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['user:read']]),
        new Get(normalizationContext: ['groups' => ['user:read:item']]),
        new Post(
            normalizationContext: ['groups' => ['user:read:item']],
            denormalizationContext: ['groups' => ['user:write']]
        ),
        new Put(
            normalizationContext: ['groups' => ['user:read:item']],
            denormalizationContext: ['groups' => ['user:write']],
            security: "is_granted('ROLE_ADMIN') or object == user"
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN') or object == user"
        )
    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read', 'user:read:item', 'announcement:read:item', 'reservation:read:item', 'reservation:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['user:read', 'user:read:item', 'user:write', 'announcement:read:item', 'reservation:read:item', 'reservation:read'])]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['user:write'])]
    #[SerializedName('password')]
    #[Assert\NotBlank(groups: ['user:write'])]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    #[Groups(['user:read', 'user:read:item', 'user:write', 'announcement:read:item', 'reservation:read:item', 'reservation:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    #[Groups(['user:read', 'user:read:item', 'user:write', 'announcement:read:item', 'reservation:read:item', 'reservation:read'])]
    private ?string $firstName = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['user:read:item', 'user:write'])]
    private ?Address $billingAddress = null;

    #[ORM\Column]
    #[Groups(['user:read:item'])]
    private ?bool $isVerified = null;

    #[ORM\Column]
    #[Groups(['user:read:item'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['user:read:item', 'user:write'])]
    private ?\DateTime $birthDate = null;

    #[ORM\Column]
    #[Groups(['user:read:item'])]
    private array $roles = [];

    #[ORM\OneToOne(mappedBy: 'resident', cascade: ['persist', 'remove'])]
    #[Groups(['user:read:item'])]
    private ?Address $address = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }
    public function getPassword(): ?string
    {
        return $this->password;
    }
    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
    public function getRoles(): array
    {
        return array_unique([...$this->roles, 'ROLE_USER']);
    }
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }
    public function eraseCredentials(): void {}
    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }
    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;
        return $this;
    }
    public function getBillingAddress(): ?Address
    {
        return $this->billingAddress;
    }
    public function setBillingAddress(?Address $billingAddress): static
    {
        $this->billingAddress = $billingAddress;
        return $this;
    }
    public function isVerified(): ?bool
    {
        return $this->isVerified;
    }
    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;
        return $this;
    }
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    public function getBirthDate(): ?\DateTime
    {
        return $this->birthDate;
    }
    public function setBirthDate(\DateTime $birthDate): static
    {
        $this->birthDate = $birthDate;
        return $this;
    }
    public function getAddress(): ?Address
    {
        return $this->address;
    }
    public function setAddress(?Address $address): static
    {
        $this->address = $address;
        if ($address && $address->getResident() !== $this) {
            $address->setResident($this);
        }
        return $this;
    }
}
