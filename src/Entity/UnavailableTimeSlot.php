<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\UnavailableTimeSlotRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['unavailable_time_slot:read']],
            security: "is_granted('ROLE_ADMIN') or object.getAnnouncement().getOwner() == user"
        ),
        new Get(
            normalizationContext: ['groups' => ['unavailable_time_slot:read']],
            security: "is_granted('ROLE_ADMIN') or object.getAnnouncement().getOwner() == user"
        ),
        new Post(
            denormalizationContext: ['groups' => ['unavailable_time_slot:write']],
            security: "is_granted('ROLE_ADMIN') or object.getAnnouncement().getOwner() == user"
        ),
        new Put(
            denormalizationContext: ['groups' => ['unavailable_time_slot:write']],
            security: "is_granted('ROLE_ADMIN') or object.getAnnouncement().getOwner() == user"
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN') or object.getAnnouncement().getOwner() == user"
        )
    ]
)]
#[ApiFilter(DateFilter::class, properties: ['startDate', 'endDate'])]
#[ApiFilter(SearchFilter::class, properties: ['reason' => 'partial'])]
#[ORM\Entity(repositoryClass: UnavailableTimeSlotRepository::class)]
class UnavailableTimeSlot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['unavailable_time_slot:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['unavailable_time_slot:read', 'unavailable_time_slot:write'])]
    #[Assert\NotBlank(message: 'Reason is required.')]
    private ?string $reason = null;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['unavailable_time_slot:read', 'unavailable_time_slot:write'])]
    #[Assert\NotNull(message: 'Start date is required.')]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['unavailable_time_slot:read', 'unavailable_time_slot:write'])]
    #[Assert\NotNull(message: 'End date is required.')]
    #[Assert\GreaterThan(propertyPath: 'startDate', message: 'End date must be after start date.')]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\ManyToOne(inversedBy: 'unavailabilities')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['unavailable_time_slot:read', 'unavailable_time_slot:write'])]
    private ?Announcement $announcement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): static
    {
        $this->reason = $reason;
        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;
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
