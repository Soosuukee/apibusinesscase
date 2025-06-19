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
use App\Repository\DisputeImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['dispute_image:read']],
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_API_TESTER') or object.getDispute().getAuthor() == user or object.getDispute().getReservation().getClient() == user"
        ),
        new Get(
            normalizationContext: ['groups' => ['dispute_image:read:item']],
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_API_TESTER') or object.getDispute().getAuthor() == user or object.getDispute().getReservation().getClient() == user"
        ),
        new Post(
            denormalizationContext: ['groups' => ['dispute_image:write']],
            normalizationContext: ['groups' => ['dispute_image:read:item']],
            security: "is_granted('ROLE_ADMIN') or object.getDispute().getAuthor() == user or object.getDispute().getReservation().getClient() == user"
        ),
        new Put(
            denormalizationContext: ['groups' => ['dispute_image:write']],
            normalizationContext: ['groups' => ['dispute_image:read:item']],
            security: "is_granted('ROLE_ADMIN') or object.getDispute().getAuthor() == user or object.getDispute().getReservation().getClient() == user"
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN') or object.getDispute().getAuthor() == user or object.getDispute().getReservation().getClient() == user"
        )
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['dispute.id' => 'exact'])]
#[ORM\Entity(repositoryClass: DisputeImageRepository::class)]
class DisputeImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['dispute_image:read', 'dispute_image:read:item', 'dispute:read:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['dispute_image:read', 'dispute_image:read:item', 'dispute_image:write', 'dispute:read:item'])]
    private ?string $imageUrl = null;

    #[ORM\ManyToOne(inversedBy: 'disputeImages')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['dispute_image:read:item', 'dispute_image:write'])]
    private ?Dispute $dispute = null;

    #[ORM\Column]
    #[Groups(['dispute_image:read:item'])]
    private ?\DateTimeImmutable $uploadedAt = null;

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

    public function getDispute(): ?Dispute
    {
        return $this->dispute;
    }

    public function setDispute(?Dispute $dispute): static
    {
        $this->dispute = $dispute;
        return $this;
    }

    public function getUploadedAt(): ?\DateTimeImmutable
    {
        return $this->uploadedAt;
    }

    public function setUploadedAt(\DateTimeImmutable $uploadedAt): static
    {
        $this->uploadedAt = $uploadedAt;
        return $this;
    }
}
