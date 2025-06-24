<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
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
use Symfony\Component\Validator\Constraints as Assert;


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
#[ApiFilter(OrderFilter::class, properties: ['city', 'country', 'continent'], arguments: ['orderParameterName' => 'order'])]
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
    #[Assert\NotBlank(message: 'City is required.')]
    #[Assert\Length(max: 255, maxMessage: 'City name cannot be longer than {{ limit }} characters.')]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['famous_location:read', 'famous_location:write'])]
    #[Assert\Length(max: 255, maxMessage: 'Description cannot be longer than {{ limit }} characters.')]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Groups(['famous_location:read', 'famous_location:write'])]
    #[Assert\NotBlank(message: 'Zip code is required.')]
    #[Assert\Length(max: 20, maxMessage: 'Zip code cannot be longer than {{ limit }} characters.')]
    #[Assert\Regex(
        pattern: '/^[0-9A-Za-z -]+$/',
        message: 'Zip code format is invalid.'
    )]
    private ?string $zipcode = null;

    #[ORM\Column(length: 255)]
    #[Groups(['famous_location:read', 'famous_location:write'])]
    #[Assert\NotBlank(message: 'Image URL or path is required.')]
    #[Assert\Length(max: 255, maxMessage: 'Image path cannot be longer than {{ limit }} characters.')]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    #[Groups(['famous_location:read', 'famous_location:write'])]
    #[Assert\NotBlank(message: 'Country is required.')]
    #[Assert\Length(max: 255, maxMessage: 'Country name cannot be longer than {{ limit }} characters.')]
    private ?string $country = null;

    #[ORM\Column(length: 255)]
    #[Groups(['famous_location:read', 'famous_location:write'])]
    #[Assert\NotBlank(message: 'Continent is required.')]
    #[Assert\Length(max: 255, maxMessage: 'Continent name cannot be longer than {{ limit }} characters.')]
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
