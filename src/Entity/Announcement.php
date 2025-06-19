<?php

namespace App\Entity;

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

#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            security: "is_granted('ROLE_ADMIN') or (is_granted('ROLE_OWNER') and object.getOwner() == user)"
        ),
        new Put(
            security: "is_granted('ROLE_ADMIN') or (is_granted('ROLE_OWNER') and object.getOwner() == user)"
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN') or (is_granted('ROLE_OWNER') and object.getOwner() == user)"
        )
    ]
)]
#[ORM\Entity(repositoryClass: AnnouncementRepository::class)]
class Announcement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column]
    private ?int $capacity = null;

    #[ORM\Column(length: 255)]
    private ?string $covering_image = null;

    #[ORM\OneToMany(mappedBy: 'announcenement', targetEntity: Message::class)]
    private Collection $messages;

    #[ORM\Column]
    private ?\DateTime $startAt = null;

    #[ORM\Column]
    private ?\DateTime $endAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(enumType: AnnouncementStatus::class)]
    private ?AnnouncementStatus $status = AnnouncementStatus::DRAFT;

    #[ORM\ManyToOne(inversedBy: 'announcements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\ManyToOne(inversedBy: 'announcements', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Address $address = null;

    #[ORM\OneToMany(targetEntity: UnavailableTimeSlot::class, mappedBy: 'announcement')]
    private Collection $unavailabilities;

    #[ORM\OneToMany(targetEntity: AnnouncementService::class, mappedBy: 'announcement', cascade: ['persist'])]
    private Collection $services;

    #[ORM\OneToMany(targetEntity: AnnouncementAmenity::class, mappedBy: 'announcement')]
    private Collection $amenities;

    #[ORM\OneToOne(mappedBy: 'announcement', cascade: ['persist', 'remove'])]
    private ?Reservation $yes = null;

    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'announcement', orphanRemoval: true)]
    private Collection $images;

    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'announcement')]
    private Collection $reviews;

    public function __construct()
    {
        $this->unavailabilities = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->amenities = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->reviews = new ArrayCollection();
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

    public function getYes(): ?Reservation
    {
        return $this->yes;
    }
    public function setYes(Reservation $yes): static
    {
        if ($yes->getAnnouncement() !== $this) {
            $yes->setAnnouncement($this);
        }
        $this->yes = $yes;
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
