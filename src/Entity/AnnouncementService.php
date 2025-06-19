<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\AnnouncementServiceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['announcement_service:read']]),
        new Get(normalizationContext: ['groups' => ['announcement_service:read']])
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['announcement.id' => 'exact'])]
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
    #[Groups(['announcement_service:read'])]
    private ?Announcement $announcement = null;

    #[ORM\ManyToOne(inversedBy: 'announcementServices')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['announcement_service:read'])]
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
