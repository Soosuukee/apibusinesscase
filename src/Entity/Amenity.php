<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\AmenityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['amenity:read']]),
        new Get(normalizationContext: ['groups' => ['amenity:read:item']]),
        new Post(
            normalizationContext: ['groups' => ['amenity:read:item']],
            denormalizationContext: ['groups' => ['amenity:write']]
        ),
        new Put(
            normalizationContext: ['groups' => ['amenity:read:item']],
            denormalizationContext: ['groups' => ['amenity:write']]
        ),
        new Delete()
    ]
)]
#[ORM\Entity(repositoryClass: AmenityRepository::class)]
class Amenity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['amenity:read', 'amenity:read:item', 'announcement:read:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['amenity:read', 'amenity:read:item', 'amenity:write', 'announcement:read:item'])]
    private ?string $name = null;

    /**
     * @var Collection<int, AnnouncementAmenity>
     */
    #[Groups(['amenity:read:item'])]
    #[ORM\OneToMany(mappedBy: 'amenity', targetEntity: AnnouncementAmenity::class, cascade: ['persist'])]
    private Collection $announcementAmenities;

    public function __construct()
    {
        $this->announcementAmenities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection<int, AnnouncementAmenity>
     */
    public function getAnnouncementAmenities(): Collection
    {
        return $this->announcementAmenities;
    }

    public function addAnnouncementAmenity(AnnouncementAmenity $announcementAmenity): static
    {
        if (!$this->announcementAmenities->contains($announcementAmenity)) {
            $this->announcementAmenities->add($announcementAmenity);
            $announcementAmenity->setAmenity($this);
        }

        return $this;
    }

    public function removeAnnouncementAmenity(AnnouncementAmenity $announcementAmenity): static
    {
        if ($this->announcementAmenities->removeElement($announcementAmenity)) {
            if ($announcementAmenity->getAmenity() === $this) {
                $announcementAmenity->setAmenity(null);
            }
        }

        return $this;
    }
}
