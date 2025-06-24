<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\ExistsFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
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
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Traits\TimestampableTrait;


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
    'title' => 'partial',
    'description' => 'partial',
    'status' => 'exact',
    'address.city' => 'partial',
    'address.country' => 'exact',
    'address.continent' => 'exact',
    'amenities.amenity.name' => 'partial',
    'services.service.name' => 'partial',
    'owner.id' => 'exact'
])]
#[ApiFilter(ExistsFilter::class, properties: ['reviews'])]
#[ApiFilter(DateFilter::class, properties: ['startAt', 'endAt', 'createdAt'])]
#[ApiFilter(RangeFilter::class, properties: ['price', 'capacity'])]
#[ApiFilter(ExistsFilter::class, properties: [
    'reviews',
    'reservations'
])]
#[ApiFilter(OrderFilter::class, properties: [
    'price',
    'capacity',
    'startAt',
    'endAt',
    'createdAt'
])]
#[ORM\Entity(repositoryClass: AnnouncementRepository::class)]
class Announcement
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['announcement:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['announcement:read', 'announcement:write'])]
    #[Assert\NotBlank(message: 'Title is required.')]
    #[Assert\Length(max: 255, maxMessage: 'Title cannot exceed {{ limit }} characters.')]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['announcement:read', 'announcement:write'])]
    #[Assert\NotBlank(message: 'Description is required.')]
    #[Assert\Length(min: 20, minMessage: 'Description must be at least {{ limit }} characters long.')]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['announcement:read', 'announcement:write'])]
    #[Assert\NotNull(message: 'Price is required.')]
    #[Assert\Positive(message: 'Price must be greater than zero.')]
    private ?int $price = null;

    #[ORM\Column]
    #[Groups(['announcement:read', 'announcement:write'])]
    #[Assert\NotNull(message: 'Capacity is required.')]
    #[Assert\Positive(message: 'Capacity must be greater than zero.')]
    private ?int $capacity = null;

    #[ORM\Column(length: 255)]
    #[Groups(['announcement:read', 'announcement:write'])]
    #[Assert\NotBlank(message: 'Cover image is required.')]
    private ?string $covering_image = null;

    #[ORM\OneToMany(mappedBy: 'announcement', targetEntity: Message::class)]
    private Collection $messages;

    #[ORM\Column]
    #[Groups(['announcement:read', 'announcement:write'])]
    #[Assert\NotNull(message: 'Start date is required.')]
    #[Assert\Type(\DateTime::class)]
    private ?\DateTime $startAt = null;

    #[ORM\Column]
    #[Groups(['announcement:read', 'announcement:write'])]
    #[Assert\NotNull(message: 'End date is required.')]
    #[Assert\Type(\DateTime::class)]
    #[Assert\GreaterThan(propertyPath: 'startAt', message: 'End date must be after start date.')]
    #[Assert\Length(max: 255, maxMessage: 'Cover image path is too long.')]
    private ?\DateTime $endAt = null;

    #[ORM\Column(enumType: AnnouncementStatus::class)]
    #[Groups(['announcement:read', 'announcement:write'])]
    #[Assert\NotNull(message: 'Status is required.')]
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

    #[ORM\OneToMany(mappedBy: 'announcement', targetEntity: Reservation::class, cascade: ['persist'])]
    #[Groups(['announcement:read'])]
    private Collection $reservations;

    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'announcement', orphanRemoval: true)]
    #[Groups(['announcement:read'])]
    private Collection $images;

    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'announcement')]
    #[Groups(['announcement:read'])]
    private Collection $reviews;

    /**
     * @var Collection<int, Resident>
     */
    #[ORM\OneToMany(targetEntity: Resident::class, mappedBy: 'announcement')]
    private Collection $residents;

    /**
     * @var Collection<int, Amenity>
     */
    #[ORM\ManyToMany(targetEntity: Amenity::class, inversedBy: 'announcements')]
    private Collection $amenities;

    /**
     * @var Collection<int, Service>
     */
    #[ORM\ManyToMany(targetEntity: Service::class, inversedBy: 'announcements')]
    private Collection $services;

    public function __construct()
    {
        $this->unavailabilities = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->residents = new ArrayCollection();
        $this->amenities = new ArrayCollection();
        $this->services = new ArrayCollection();
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

    /**
     * @return Collection<int, Resident>
     */
    public function getResidents(): Collection
    {
        return $this->residents;
    }

    public function addResident(Resident $resident): static
    {
        if (!$this->residents->contains($resident)) {
            $this->residents->add($resident);
            $resident->setAnnouncement($this);
        }

        return $this;
    }

    public function removeResident(Resident $resident): static
    {
        if ($this->residents->removeElement($resident)) {
            // set the owning side to null (unless already changed)
            if ($resident->getAnnouncement() === $this) {
                $resident->setAnnouncement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Amenity>
     */
    public function getAmenities(): Collection
    {
        return $this->amenities;
    }

    public function addAmenity(Amenity $amenity): static
    {
        if (!$this->amenities->contains($amenity)) {
            $this->amenities->add($amenity);
        }

        return $this;
    }

    public function removeAmenity(Amenity $amenity): static
    {
        $this->amenities->removeElement($amenity);

        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): static
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
        }

        return $this;
    }

    public function removeService(Service $service): static
    {
        $this->services->removeElement($service);

        return $this;
    }
}
