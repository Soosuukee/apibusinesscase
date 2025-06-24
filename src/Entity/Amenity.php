<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\AmenityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['amenity:read']]
        ),
        new Get(
            normalizationContext: ['groups' => ['amenity:read:item']]
        ),
        new Post(
            normalizationContext: ['groups' => ['amenity:read:item']],
            denormalizationContext: ['groups' => ['amenity:write']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Put(
            normalizationContext: ['groups' => ['amenity:read:item']],
            denormalizationContext: ['groups' => ['amenity:write']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN')"
        )
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial'])]
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
    #[Assert\NotBlank(message: 'Name is required.')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Name cannot be longer than {{ limit }} characters.'
    )]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['amenity:read', 'amenity:read:item', 'amenity:write', 'announcement:read:item'])]
    #[Assert\NotBlank(message: 'Description is required.')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Description cannot be longer than {{ limit }} characters.'
    )]
    private ?string $description = null;

    /**
     * @var Collection<int, Announcement>
     */
    #[ORM\ManyToMany(targetEntity: Announcement::class, mappedBy: 'amenities')]
    private Collection $announcements;

    public function __construct()
    {
        $this->announcements = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Collection<int, Announcement>
     */
    public function getAnnouncements(): Collection
    {
        return $this->announcements;
    }

    public function addAnnouncement(Announcement $announcement): static
    {
        if (!$this->announcements->contains($announcement)) {
            $this->announcements->add($announcement);
            $announcement->addAmenity($this);
        }

        return $this;
    }

    public function removeAnnouncement(Announcement $announcement): static
    {
        if ($this->announcements->removeElement($announcement)) {
            $announcement->removeAmenity($this);
        }

        return $this;
    }
}
