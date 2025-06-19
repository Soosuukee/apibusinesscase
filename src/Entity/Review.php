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
use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

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
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['review:read', 'review:read:item'])]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['review:read', 'review:read:item', 'review:write'])]
    private ?float $note = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['review:read', 'review:read:item', 'review:write'])]
    private ?string $comment = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['review:read:item', 'review:owner:write'])]
    private ?string $ownerReply = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['review:read', 'review:read:item'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['review:read:item'])]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['review:read:item', 'review:write'])]
    private ?Announcement $announcement = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

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

    public function setOwnerReply(?string $ownerReply): static
    {
        $this->ownerReply = $ownerReply;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
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
