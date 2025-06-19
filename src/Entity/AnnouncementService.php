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
use App\Repository\AnnouncementServiceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['announcement_service:read']]),
        new Get(normalizationContext: ['groups' => ['announcement_service:read']]),
        new Post(
            security: "is_granted('ROLE_ADMIN') or object.getAnnouncement().getOwner() == user",
            denormalizationContext: ['groups' => ['announcement_service:write']]
        ),
        new Put(
            security: "is_granted('ROLE_ADMIN') or object.getAnnouncement().getOwner() == user",
            denormalizationContext: ['groups' => ['announcement_service:write']]
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN') or object.getAnnouncement().getOwner() == user"
        )
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'announcement.id' => 'exact',
    'service.id' => 'exact'
])]
#[ORM\Entity(repositoryClass: AnnouncementServiceRepository::class)]
class AnnouncementService
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['announcement_service:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['announcement_service:read', 'announcement_service:write'])]
    private ?Announcement $announcement = null;

    #[ORM\ManyToOne(inversedBy: 'announcementServices')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['announcement_service:read', 'announcement_service:write'])]
    private ?Service $service = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): static
    {
        $this->service = $service;
        return $this;
    }
}
