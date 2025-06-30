<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['image:read']]),
        new Get(normalizationContext: ['groups' => ['image:read:item']]),
        new Post(
            normalizationContext: ['groups' => ['image:read:item']],
            denormalizationContext: ['groups' => ['image:write']],
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_OWNER')"
        ),
        new Put(
            normalizationContext: ['groups' => ['image:read:item']],
            denormalizationContext: ['groups' => ['image:write']],
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_OWNER')"
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_OWNER')"
        )
    ]
)]
#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['image:read', 'image:read:item', 'announcement:read:item', 'announcement:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['image:read', 'image:read:item', 'image:write', 'announcement:read:item', 'announcement:read'])]
    #[Assert\NotBlank(message: 'Image URL is required.')]
    #[Assert\Length(max: 255, maxMessage: 'Image URL cannot be longer than {{ limit }} characters.')]
    #[Assert\Url(message: 'The image URL must be valid.')]
    private ?string $imageUrl = null;
    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['image:read'])]
    private ?\DateTimeImmutable $uploadedAt = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['image:read:item'])]
    #[Assert\NotNull(message: 'An image must be linked to an announcement.')]
    private ?Announcement $announcement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    public function getUploadedAt(): ?\DateTimeImmutable
    {
        return $this->uploadedAt;
    }

    #[ORM\PrePersist]
    public function setUploadedAt(): void
    {
        $this->uploadedAt = new \DateTimeImmutable();
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
