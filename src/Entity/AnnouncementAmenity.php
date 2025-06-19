<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\AnnouncementAmenityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['announcement_amenity:read']]),
        new Get(normalizationContext: ['groups' => ['announcement_amenity:read']]),
        new Post(
            securityPostDenormalize: "is_granted('ROLE_ADMIN') or object.getAnnouncement().getOwner() == user",
            denormalizationContext: ['groups' => ['announcement_amenity:write']]
        ),
        new Put(
            securityPostDenormalize: "is_granted('ROLE_ADMIN') or object.getAnnouncement().getOwner() == user",
            denormalizationContext: ['groups' => ['announcement_amenity:write']]
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN') or object.getAnnouncement().getOwner() == user"
        )
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'announcement.id' => 'exact',
    'amenity.id' => 'exact'
])]
#[ORM\Entity(repositoryClass: AnnouncementAmenityRepository::class)]
class AnnouncementAmenity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['announcement_amenity:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'announcementAmenities')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['announcement_amenity:read', 'announcement_amenity:write'])]
    private ?Amenity $amenity = null;

    #[ORM\ManyToOne(inversedBy: 'amenities')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['announcement_amenity:read', 'announcement_amenity:write'])]
    private ?Announcement $announcement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmenity(): ?Amenity
    {
        return $this->amenity;
    }

    public function setAmenity(?Amenity $amenity): static
    {
        $this->amenity = $amenity;
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
