<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Address;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']],
    operations: [
        new GetCollection(security: "is_granted('ROLE_ADMIN')"),
        new Get(security: "is_granted('ROLE_ADMIN') or object == user"),
        new Post(),
        new Put(security: "is_granted('ROLE_ADMIN') or object == user"),
        new Delete(security: "is_granted('ROLE_ADMIN') or object == user")
    ]
)]

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read', 'address:admin:read', 'announcement:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['user:read'])]
    private array $roles = ['ROLE_USER'];

    #[ORM\Column]
    #[Groups(['user:write'])]
    #[Assert\NotBlank(message: 'Password is needed.')]
    #[Assert\Length(
        min: 8,
        minMessage: 'Password should be at least {{ limit }} long.'
    )]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'announcement:read', 'user:write'])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'announcement:read', 'user:write'])]
    private ?string $lastName = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['user:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['user:read', 'user:write'])]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\ManyToOne]
    #[Groups(['user:read', 'user:write'])]
    private ?Address $address = null;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Announcement::class)]
    #[Groups(['user:read'])]
    private Collection $announcements;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Dispute::class)]
    #[Groups(['user:read'])]
    private Collection $disputes;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Reservation::class)]
    #[Groups(['user:read'])]
    private Collection $reservations;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Review::class)]
    private Collection $reviews;

    /**
     * @var Collection<int, Resident>
     */
    #[ORM\OneToMany(targetEntity: Resident::class, mappedBy: 'resident')]
    private Collection $residents;

    public function __construct()
    {
        $this->announcements = new ArrayCollection();
        $this->disputes = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->residents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
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

    public function getAnnouncements(): Collection
    {
        return $this->announcements;
    }

    public function addAnnouncement(Announcement $announcement): static
    {
        if (!$this->announcements->contains($announcement)) {
            $this->announcements->add($announcement);
            $announcement->setOwner($this);
        }

        return $this;
    }

    public function removeAnnouncement(Announcement $announcement): static
    {
        if ($this->announcements->removeElement($announcement)) {
            if ($announcement->getOwner() === $this) {
                $announcement->setOwner(null);
            }
        }

        return $this;
    }

    public function getDisputes(): Collection
    {
        return $this->disputes;
    }

    public function addDispute(Dispute $dispute): static
    {
        if (!$this->disputes->contains($dispute)) {
            $this->disputes->add($dispute);
            $dispute->setAuthor($this);
        }

        return $this;
    }

    public function removeDispute(Dispute $dispute): static
    {
        if ($this->disputes->removeElement($dispute)) {
            if ($dispute->getAuthor() === $this) {
                $dispute->setAuthor(null);
            }
        }

        return $this;
    }

    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setClient($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            if ($reservation->getClient() === $this) {
                $reservation->setClient(null);
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
            $review->setAuthor($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            if ($review->getAuthor() === $this) {
                $review->setAuthor(null);
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
            $resident->setResident($this);
        }

        return $this;
    }

    public function removeResident(Resident $resident): static
    {
        if ($this->residents->removeElement($resident)) {
            // set the owning side to null (unless already changed)
            if ($resident->getResident() === $this) {
                $resident->setResident(null);
            }
        }

        return $this;
    }
}
