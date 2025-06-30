<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Enum\ReservationStatus;
use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['reservation:read']],
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_OWNER') or is_granted('ROLE_USER')"
        ),
        new Get(
            normalizationContext: ['groups' => ['reservation:read:item']],
            security: "is_granted('ROLE_ADMIN') or object.getClient() == user or object.getAnnouncement().getOwner() == user"
        ),
        new Post(
            denormalizationContext: ['groups' => ['reservation:write']],
            security: "is_granted('ROLE_USER')"
        ),
        new Put(
            denormalizationContext: ['groups' => ['reservation:write']],
            security: "is_granted('ROLE_ADMIN') or object.getClient() == user"
        ),
        new Patch(
            denormalizationContext: ['groups' => ['reservation:write']],
            security: "is_granted('ROLE_ADMIN') or object.getClient() == user"
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN') or object.getClient() == user"
        )
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'status' => 'exact',
    'client.id' => 'exact',
    'announcement.id' => 'exact'
])]
#[ApiFilter(DateFilter::class, properties: ['startedAt', 'endAt'])]
#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['reservation:read', 'reservation:read:item'])]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['reservation:read', 'reservation:read:item'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['reservation:read', 'reservation:read:item'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    #[Groups(['reservation:read', 'reservation:read:item', 'reservation:write'])]
    private ?\DateTime $startedAt = null;

    #[ORM\Column]
    #[Groups(['reservation:read', 'reservation:read:item', 'reservation:write'])]
    private ?\DateTime $endAt = null;

    #[ORM\Column(enumType: ReservationStatus::class)]
    #[Groups(['reservation:read', 'reservation:read:item', 'reservation:write'])]
    private ?ReservationStatus $status = null;

    #[ORM\Column]
    #[Groups(['reservation:read', 'reservation:read:item', 'reservation:write'])]
    private ?float $totalPaid = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['reservation:read', 'reservation:read:item', 'reservation:write'])]
    private ?User $client = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['reservation:read', 'reservation:read:item', 'reservation:write'])]
    private ?Announcement $announcement = null;

    /**
     * @var Collection<int, Dispute>
     */
    #[ORM\OneToMany(targetEntity: Dispute::class, mappedBy: 'reservation')]
    private Collection $disputes;

    public function __construct()
    {
        $this->disputes = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getStartedAt(): ?\DateTime
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTime $startedAt): static
    {
        $this->startedAt = $startedAt;
        return $this;
    }

    public function getEndAt(): ?\DateTime
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTime $endAt): static
    {
        $this->endAt = $endAt;
        return $this;
    }

    public function getStatus(): ?ReservationStatus
    {
        return $this->status;
    }

    public function setStatus(ReservationStatus $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getTotalPaid(): ?float
    {
        return $this->totalPaid;
    }

    public function setTotalPaid(float $totalPaid): static
    {
        $this->totalPaid = $totalPaid;
        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): static
    {
        $this->client = $client;
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

    /**
     * @return Collection<int, Dispute>
     */
    public function getDisputes(): Collection
    {
        return $this->disputes;
    }

    public function addDispute(Dispute $dispute): static
    {
        if (!$this->disputes->contains($dispute)) {
            $this->disputes->add($dispute);
            $dispute->setReservation($this);
        }

        return $this;
    }

    public function removeDispute(Dispute $dispute): static
    {
        if ($this->disputes->removeElement($dispute)) {
            // set the owning side to null (unless already changed)
            if ($dispute->getReservation() === $this) {
                $dispute->setReservation(null);
            }
        }

        return $this;
    }
}
