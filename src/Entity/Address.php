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
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['address:read']],
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_API_TESTER')"
        ),
        new Get(
            normalizationContext: ['groups' => ['address:read', 'address:admin:read']]
        ),
        new Post(
            normalizationContext: ['groups' => ['address:read']],
            denormalizationContext: ['groups' => ['address:write']],
            security: "is_granted('IS_AUTHENTICATED_FULLY')"
        ),
        new Put(
            normalizationContext: ['groups' => ['address:read']],
            denormalizationContext: ['groups' => ['address:write']],
            security: "is_granted('IS_AUTHENTICATED_FULLY')"
        ),
        new Delete(
            security: "is_granted('IS_AUTHENTICATED_FULLY')"
        )
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'city' => 'partial',
    'zipCode' => 'exact',
    'country' => 'partial'
])]
#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['address:read', 'address:read:item', 'announcement:read', 'announcement:read:item'])]
    private ?int $id = null;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['address:read', 'address:write'])]
    private bool $available = true;

    #[ORM\Column(nullable: true)]
    #[Groups(['address:read:item', 'address:write', 'announcement:read:item'])]
    #[Assert\Positive(message: 'Number must be positive.')]

    private ?int $number = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['address:read:item', 'address:write', 'announcement:read:item'])]
    private ?string $complement = null;

    #[ORM\Column(length: 255)]
    #[Groups(['address:read', 'address:read:item', 'address:write', 'announcement:read', 'announcement:read:item'])]
    #[Assert\NotBlank(message: 'Street is required.')]
    #[Assert\Length(max: 255, maxMessage: 'Street cannot exceed {{ limit }} characters.')]

    private ?string $street = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['address:read:item', 'address:write', 'announcement:read', 'announcement:read:item'])]
    private ?string $district = null;

    #[ORM\Column(length: 20)]
    #[Groups(['address:read', 'address:read:item', 'address:write', 'announcement:read', 'announcement:read:item'])]
    #[Assert\NotBlank(message: 'Zip code is required.')]
    #[Assert\Length(max: 20, maxMessage: 'Zip code cannot exceed {{ limit }} characters.')]
    #[Assert\Regex(
        pattern: '/^[0-9A-Za-z -]+$/',
        message: 'Please enter a valid zip code.'
    )]

    private ?string $zipCode = null;

    #[ORM\Column(length: 255)]
    #[Groups(['address:read', 'address:read:item', 'address:write', 'announcement:read', 'announcement:read:item'])]
    #[Assert\NotBlank(message: 'City is required.')]
    #[Assert\Length(max: 255, maxMessage: 'City name cannot exceed {{ limit }} characters.')]

    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['address:read:item', 'address:write', 'announcement:read', 'announcement:read:item'])]
    private ?string $state = null;

    #[ORM\Column(length: 255)]
    #[Groups(['address:read', 'address:read:item', 'address:write', 'announcement:read', 'announcement:read:item'])]
    #[Assert\NotBlank(message: 'Country is required.')]
    #[Assert\Length(max: 255, maxMessage: 'Country name cannot exceed {{ limit }} characters.')]


    private ?string $country = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['address:read:item', 'address:write', 'announcement:read', 'announcement:read:item'])]
    #[Assert\Range(min: -180, max: 180, notInRangeMessage: 'Longitude must be between -180 and 180.')]

    private ?float $longitude = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['address:read:item', 'address:write', 'announcement:read', 'announcement:read:item'])]
    #[Assert\Range(min: -90, max: 90, notInRangeMessage: 'Latitude must be between -90 and 90.')]

    private ?float $latitude = null;

    #[ORM\OneToMany(targetEntity: Announcement::class, mappedBy: 'address')]
    #[Groups(['address:read:item', 'announcement:read', 'announcement:read:item'])]
    private Collection $announcements;

    public function __construct()
    {
        $this->announcements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): static
    {
        $this->number = $number;
        return $this;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(?string $complement): static
    {
        $this->complement = $complement;
        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;
        return $this;
    }

    public function getDistrict(): ?string
    {
        return $this->district;
    }

    public function setDistrict(?string $district): static
    {
        $this->district = $district;
        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): static
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;
        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): static
    {
        $this->state = $state;
        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;
        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;
        return $this;
    }

    /** @return Collection<int, Announcement> */
    public function getAnnouncements(): Collection
    {
        return $this->announcements;
    }

    public function addAnnouncement(Announcement $announcement): static
    {
        if (!$this->announcements->contains($announcement)) {
            $this->announcements->add($announcement);
            $announcement->setAddress($this);
        }
        return $this;
    }

    public function removeAnnouncement(Announcement $announcement): static
    {
        if ($this->announcements->removeElement($announcement)) {
            if ($announcement->getAddress() === $this) {
                $announcement->setAddress(null);
            }
        }
        return $this;
    }

    public function isAvailable(): bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): static
    {
        $this->available = $available;
        return $this;
    }
}
