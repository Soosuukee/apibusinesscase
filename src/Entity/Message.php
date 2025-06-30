<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['message:read']],
    denormalizationContext: ['groups' => ['message:write']],
    operations: [
        new GetCollection(
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Get(
            security: "is_granted('ROLE_ADMIN') or object.getSender() == user or object.getRecipient() == user"
        ),
        new Post(
            securityPostDenormalize: "is_granted('ROLE_USER') and (object.getAnnouncement().getOwner() == user or object.getRecipient() == user)"
        ),
        new Put(
            security: "is_granted('ROLE_ADMIN') or object.getSender() == user"
        ),
        new Patch(
            denormalizationContext: ['groups' => ['message:write']],
            security: "is_granted('ROLE_ADMIN') or object.getRecipient() == user"
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN')"
        )
    ]
)]

#[ApiFilter(SearchFilter::class, properties: [
    'sender.id' => 'exact',
    'recipient.id' => 'exact',
    'content' => 'partial'
])]
#[ApiFilter(DateFilter::class, properties: ['sentAt'])]
#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['message:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['message:read', 'message:write'])]
    private ?string $content = null;

    #[ORM\Column]
    #[Groups(['message:read'])]
    private ?\DateTimeImmutable $sentAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['message:read'])]
    private ?\DateTimeImmutable $readAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['message:read'])]
    private ?\DateTimeImmutable $editedAt = null;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['message:read', 'message:write'])]
    private bool $isRead = false;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['message:read', 'message:write'])]
    private ?User $sender = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['message:read', 'message:write'])]
    private ?User $recipient = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['message:read', 'message:write'])]
    private ?Announcement $announcement = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;
        return $this;
    }

    public function getSentAt(): ?\DateTimeImmutable
    {
        return $this->sentAt;
    }

    #[ORM\PrePersist]
    public function setSentAt(): void
    {
        $this->sentAt = new \DateTimeImmutable();
    }

    public function getReadAt(): ?\DateTimeImmutable
    {
        return $this->readAt;
    }

    public function setReadAt(?\DateTimeImmutable $readAt): static
    {
        $this->readAt = $readAt;
        return $this;
    }

    public function getEditedAt(): ?\DateTimeImmutable
    {
        return $this->editedAt;
    }

    #[ORM\PreUpdate]
    public function setEditedAt(): void
    {
        $this->editedAt = new \DateTimeImmutable();
    }

    public function isRead(): bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): static
    {
        $this->isRead = $isRead;
        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): static
    {
        $this->sender = $sender;
        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $recipient): static
    {
        $this->recipient = $recipient;
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
