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

    #[ORM\Column(nullable: true)]
    #[Groups(['address:read:item', 'address:write'])]
    private ?int $number = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['address:read:item', 'address:write'])]
    private ?string $complement = null;

    #[ORM\Column(length: 255)]
    #[Groups(['address:read', 'address:read:item', 'address:write'])]
    private ?string $street = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['address:read:item', 'address:write'])]
    private ?string $district = null;

    #[ORM\Column(length: 20)]
    #[Groups(['address:read', 'address:read:item', 'address:write'])]
    private ?string $zipCode = null;

    #[ORM\Column(length: 255)]
    #[Groups(['address:read', 'address:read:item', 'address:write'])]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['address:read:item', 'address:write'])]
    private ?string $state = null;

    #[ORM\Column(length: 255)]
    #[Groups(['address:read', 'address:read:item', 'address:write'])]
    private ?string $country = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['address:read:item', 'address:write'])]
    private ?float $longitude = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['address:read:item', 'address:write'])]
    private ?float $latitude = null;

    #[ORM\OneToOne(inversedBy: 'address', cascade: ['persist', 'remove'])]
    #[Groups(['address:admin:read'])]
    private ?User $resident = null;

    #[ORM\OneToMany(targetEntity: Announcement::class, mappedBy: 'address')]
    #[Groups(['address:read:item'])]
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

    public function getResident(): ?User
    {
        return $this->resident;
    }
    public function setResident(User $resident): static
    {
        if ($resident->getAddress() !== $this) {
            $resident->setAddress($this);
        }
        $this->resident = $resident;
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
}
