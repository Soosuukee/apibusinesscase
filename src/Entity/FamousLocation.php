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
use App\Repository\FamousLocationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['famous_location:read']]),
        new Get(normalizationContext: ['groups' => ['famous_location:read']]),
        new Post(
            denormalizationContext: ['groups' => ['famous_location:write']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Put(
            denormalizationContext: ['groups' => ['famous_location:write']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN')"
        )
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['city' => 'partial', 'country' => 'partial', 'continent' => 'partial'])]
#[ORM\Entity(repositoryClass: FamousLocationRepository::class)]
class FamousLocation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['famous_location:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['famous_location:read', 'famous_location:write'])]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['famous_location:read', 'famous_location:write'])]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Groups(['famous_location:read', 'famous_location:write'])]
    private ?string $zipcode = null;

    #[ORM\Column(length: 255)]
    #[Groups(['famous_location:read', 'famous_location:write'])]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    #[Groups(['famous_location:read', 'famous_location:write'])]
    private ?string $country = null;

    #[ORM\Column(length: 255)]
    #[Groups(['famous_location:read', 'famous_location:write'])]
    private ?string $continent = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): static
    {
        $this->zipcode = $zipcode;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;
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

    public function getContinent(): ?string
    {
        return $this->continent;
    }

    public function setContinent(string $continent): static
    {
        $this->continent = $continent;
        return $this;
    }
}
