<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\ExistsFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use App\Enum\AnnouncementStatus;
use App\Repository\AnnouncementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['announcement:read']],
    denormalizationContext: ['groups' => ['announcement:write']],
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_OWNER')"
        ),
        new Put(
            security: "is_granted('ROLE_ADMIN') or object.getOwner() == user"
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN') or object.getOwner() == user"
        )
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'amenities.amenity.name' => 'partial',
    'services.service.name' => 'partial',
    'address.city' => 'partial',
    'address.country' => 'exact',
    'address.continent' => 'exact'
])]
#[ApiFilter(ExistsFilter::class, properties: ['reviews'])]
#[ApiFilter(DateFilter::class, properties: ['startAt', 'endAt'])]
#[ApiFilter(RangeFilter::class, properties: ['price'])]
#[ORM\Entity(repositoryClass: AnnouncementRepository::class)]
class Announcement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['announcement:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['announcement:read', 'announcement:write'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['announcement:read', 'announcement:write'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['announcement:read', 'announcement:write'])]
    private ?int $price = null;

    #[ORM\Column]
    #[Groups(['announcement:read', 'announcement:write'])]
    private ?int $capacity = null;

    #[ORM\Column(length: 255)]
    #[Groups(['announcement:read', 'announcement:write'])]
    private ?string $covering_image = null;

    #[ORM\OneToMany(mappedBy: 'announcement', targetEntity: Message::class)]
    private Collection $messages;

    #[ORM\Column]
    #[Groups(['announcement:read', 'announcement:write'])]
    private ?\DateTime $startAt = null;

    #[ORM\Column]
    #[Groups(['announcement:read', 'announcement:write'])]
    private ?\DateTime $endAt = null;

    #[ORM\Column]
    #[Groups(['announcement:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(enumType: AnnouncementStatus::class)]
    #[Groups(['announcement:read', 'announcement:write'])]
    private ?AnnouncementStatus $status = AnnouncementStatus::DRAFT;

    #[ORM\ManyToOne(inversedBy: 'announcements')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['announcement:read', 'announcement:write'])]
    private ?User $owner = null;

    #[ORM\ManyToOne(inversedBy: 'announcements', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['announcement:read', 'announcement:write'])]
    private ?Address $address = null;

    #[ORM\OneToMany(targetEntity: UnavailableTimeSlot::class, mappedBy: 'announcement')]
    #[Groups(['announcement:read'])]
    private Collection $unavailabilities;

    #[ORM\OneToMany(targetEntity: AnnouncementService::class, mappedBy: 'announcement', cascade: ['persist'])]
    #[Groups(['announcement:read'])]
    private Collection $services;

    #[ORM\OneToMany(targetEntity: AnnouncementAmenity::class, mappedBy: 'announcement')]
    #[Groups(['announcement:read'])]
    private Collection $amenities;

    #[ORM\OneToMany(mappedBy: 'announcement', targetEntity: Reservation::class, cascade: ['persist'])]
    #[Groups(['announcement:read'])]
    private Collection $reservations;

    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'announcement', orphanRemoval: true)]
    #[Groups(['announcement:read'])]
    private Collection $images;

    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'announcement')]
    #[Groups(['announcement:read'])]
    private Collection $reviews;

    public function __construct()
    {
        $this->unavailabilities = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->amenities = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTitle(): ?string
    {
        return $this->title;
    }
    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }
    public function getPrice(): ?int
    {
        return $this->price;
    }
    public function setPrice(int $price): static
    {
        $this->price = $price;
        return $this;
    }
    public function getCapacity(): ?int
    {
        return $this->capacity;
    }
    public function setCapacity(int $capacity): static
    {
        $this->capacity = $capacity;
        return $this;
    }
    public function getCoveringImage(): ?string
    {
        return $this->covering_image;
    }
    public function setCoveringImage(string $covering_image): static
    {
        $this->covering_image = $covering_image;
        return $this;
    }
    public function getStartAt(): ?\DateTime
    {
        return $this->startAt;
    }
    public function setStartAt(\DateTime $startAt): static
    {
        $this->startAt = $startAt;
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
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    public function getStatus(): ?AnnouncementStatus
    {
        return $this->status;
    }
    public function setStatus(AnnouncementStatus $status): static
    {
        $this->status = $status;
        return $this;
    }
    public function getOwner(): ?User
    {
        return $this->owner;
    }
    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;
        return $this;
    }
    public function getAddress(): ?Address
    {
        return $this->address;
    }
    public function setAddress(?Address $address): static
    {
        $this->address = $address;
        return $this;
    }
    /** @return Collection<int, Reservation> */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setAnnouncement($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            if ($reservation->getAnnouncement() === $this) {
                $reservation->setAnnouncement(null);
            }
        }

        return $this;
    }
    public function getMessages(): Collection
    {
        return $this->messages;
    }
    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setAnnouncement($this);
        }
        return $this;
    }
    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            if ($message->getAnnouncement() === $this) {
                $message->setAnnouncement(null);
            }
        }
        return $this;
    }
    public function getUnavailabilities(): Collection
    {
        return $this->unavailabilities;
    }
    public function addUnavailability(UnavailableTimeSlot $unavailability): static
    {
        if (!$this->unavailabilities->contains($unavailability)) {
            $this->unavailabilities->add($unavailability);
            $unavailability->setAnnouncement($this);
        }
        return $this;
    }
    public function removeUnavailability(UnavailableTimeSlot $unavailability): static
    {
        if ($this->unavailabilities->removeElement($unavailability)) {
            if ($unavailability->getAnnouncement() === $this) {
                $unavailability->setAnnouncement(null);
            }
        }
        return $this;
    }
    public function getServices(): Collection
    {
        return $this->services;
    }
    public function addService(AnnouncementService $service): static
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setAnnouncement($this);
        }
        return $this;
    }
    public function removeService(AnnouncementService $service): static
    {
        if ($this->services->removeElement($service)) {
            if ($service->getAnnouncement() === $this) {
                $service->setAnnouncement(null);
            }
        }
        return $this;
    }
    public function getAmenities(): Collection
    {
        return $this->amenities;
    }
    public function addAmenity(AnnouncementAmenity $amenity): static
    {
        if (!$this->amenities->contains($amenity)) {
            $this->amenities->add($amenity);
            $amenity->setAnnouncement($this);
        }
        return $this;
    }
    public function removeAmenity(AnnouncementAmenity $amenity): static
    {
        if ($this->amenities->removeElement($amenity)) {
            if ($amenity->getAnnouncement() === $this) {
                $amenity->setAnnouncement(null);
            }
        }
        return $this;
    }
    public function getImages(): Collection
    {
        return $this->images;
    }
    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setAnnouncement($this);
        }
        return $this;
    }
    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            if ($image->getAnnouncement() === $this) {
                $image->setAnnouncement(null);
            }
        }
        return $this;
    }
    public function getReviews(): Collection
    {
        return $this->reviews;
    }
    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setAnnouncement($this);
        }
        return $this;
    }
    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            if ($review->getAnnouncement() === $this) {
                $review->setAnnouncement(null);
            }
        }
        return $this;
    }
}
