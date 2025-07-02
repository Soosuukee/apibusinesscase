<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\Patch;
use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['review:read']]),
        new Get(normalizationContext: ['groups' => ['review:read:item']]),
        new Post(
            denormalizationContext: ['groups' => ['review:write']],
            security: "is_granted('ROLE_USER')"
        ),
        new Put(
            denormalizationContext: ['groups' => ['review:write', 'review:owner:write']],
            security: "is_granted('ROLE_ADMIN') or object.getAuthor() == user or object.getAnnouncement().getOwner() == user"
        ),
        new Patch(
            denormalizationContext: ['groups' => ['review:write', 'review:owner:write']],
            security: "is_granted('ROLE_ADMIN') or object.getAuthor() == user or object.getAnnouncement().getOwner() == user"
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN') or object.getAuthor() == user"
        )
    ]
)]

#[ApiFilter(SearchFilter::class, properties: [
    'announcement.id' => 'exact',
    'author.id' => 'exact'
])]
#[ApiFilter(OrderFilter::class, properties: [
    'note' => 'DESC',
    'createdAt' => 'DESC'
], arguments: ['orderParameterName' => 'order'])]
#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['review:read', 'review:read:item'])]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['review:read', 'review:read:item', 'review:write'])]
    #[Assert\Range(
        notInRangeMessage: 'The note must be between {{ min }} and {{ max }}.',
        min: 0,
        max: 5
    )]
    private ?float $note = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['review:read', 'review:read:item', 'review:write'])]
    private ?string $comment = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['review:read:item', 'review:owner:write'])]
    private ?string $ownerReply = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['review:read', 'review:read:item'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['review:read', 'review:read:item'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['review:read:item'])]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['review:read:item', 'review:write'])]
    private ?Announcement $announcement = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(?float $note): static
    {
        $this->note = $note;
        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;
        return $this;
    }

    public function getOwnerReply(): ?string
    {
        return $this->ownerReply;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function setOwnerReply(?string $ownerReply): static
    {
        $this->ownerReply = $ownerReply;
        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;
        return $this;
    }

    public function getAnnouncement(): ?Announcement
    {
        return $this->announcement;
    }

    public function setAnnouncement(?Announcement $announcement): static
    {
        $this->announcement = $announcement;
        return $this;
    }
}
