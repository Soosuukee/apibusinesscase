<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\ExistsFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\ResidentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResidentRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['resident:read']],
    denormalizationContext: ['groups' => ['resident:write']],
    operations: [
        new GetCollection(security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_OWNER')"),
        new Post(security: "is_granted('ROLE_USER')"),
        new Delete(security: "is_granted('ROLE_ADMIN') or object.getResident() == user")
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'resident.id' => 'exact',
    'resident.email' => 'partial',
    'announcement.id' => 'exact',
])]
#[ApiFilter(DateFilter::class, properties: ['startedAt', 'endedAt'])]
#[ApiFilter(ExistsFilter::class, properties: ['endedAt'])]


class Resident
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['announcement:read', 'resident:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'residents')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['announcement:read', 'resident:read', 'resident:write'])]
    private ?User $resident = null;

    #[ORM\ManyToOne(inversedBy: 'residents')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['resident:read', 'resident:write'])]
    private ?Announcement $announcement = null;

    #[ORM\Column]
    #[Groups(['announcement:read', 'resident:read', 'resident:write'])]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['announcement:read', 'resident:read', 'resident:write'])]
    private ?\DateTimeImmutable $endedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResident(): ?User
    {
        return $this->resident;
    }

    public function setResident(?User $resident): static
    {
        $this->resident = $resident;

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

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeImmutable $startedAt): static
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getEndedAt(): ?\DateTimeImmutable
    {
        return $this->endedAt;
    }

    public function setEndedAt(?\DateTimeImmutable $endedAt): static
    {
        $this->endedAt = $endedAt;

        return $this;
    }
}
