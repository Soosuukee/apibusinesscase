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
use App\Repository\DisputeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Traits\TimestampableTrait;



#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['dispute:read']],
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_API_TESTER')"
        ),
        new Get(
            normalizationContext: ['groups' => ['dispute:read:item']],
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_API_TESTER') or object.getAuthor() == user or object.getReservation().getClient() == user"
        ),
        new Post(
            denormalizationContext: ['groups' => ['dispute:write']],
            security: "is_granted('ROLE_ADMIN') or object.getReservation().getClient() == user or object.getAuthor() == user"
        ),
        new Put(
            denormalizationContext: ['groups' => ['dispute:write']],
            security: "is_granted('ROLE_ADMIN') or object.getReservation().getClient() == user or object.getAuthor() == user"
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN') or object.getReservation().getClient() == user or object.getAuthor() == user"
        )
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial', 'isResolved' => 'exact', 'author.id' => 'exact'])]
#[ORM\Entity(repositoryClass: DisputeRepository::class)]
class Dispute
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['dispute:read', 'dispute:read:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['dispute:read', 'dispute:read:item', 'dispute:write'])]
    #[Assert\NotBlank(message: 'Title is required.')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Title cannot be longer than {{ limit }} characters.'
    )]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['dispute:read:item', 'dispute:write'])]
    #[Assert\NotBlank(message: 'Description is required.')]
    #[Assert\Length(
        min: 20,
        minMessage: 'Description must be at least {{ limit }} characters long.'
    )]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['dispute:read', 'dispute:read:item', 'dispute:write'])]
    #[Assert\NotNull(message: 'Resolution status is required.')]
    private ?bool $isResolved = null;

    #[ORM\ManyToOne(inversedBy: 'disputes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['dispute:read:item', 'dispute:write'])]
    #[Assert\NotNull(message: 'Author is required.')]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'disputes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['dispute:read:item', 'dispute:write'])]
    #[Assert\NotNull(message: 'Reservation is required.')]
    private ?Reservation $reservation = null;

    #[ORM\OneToMany(targetEntity: DisputeImage::class, mappedBy: 'dispute')]
    #[Groups(['dispute:read:item'])]
    private Collection $disputeImages;

    public function __construct()
    {
        $this->disputeImages = new ArrayCollection();
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

    public function isResolved(): ?bool
    {
        return $this->isResolved;
    }

    public function setIsResolved(bool $isResolved): static
    {
        $this->isResolved = $isResolved;
        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;
        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): static
    {
        $this->reservation = $reservation;
        return $this;
    }

    /**
     * @return Collection<int, DisputeImage>
     */
    public function getDisputeImages(): Collection
    {
        return $this->disputeImages;
    }

    public function addDisputeImage(DisputeImage $disputeImage): static
    {
        if (!$this->disputeImages->contains($disputeImage)) {
            $this->disputeImages->add($disputeImage);
            $disputeImage->setDispute($this);
        }

        return $this;
    }

    public function removeDisputeImage(DisputeImage $disputeImage): static
    {
        if ($this->disputeImages->removeElement($disputeImage)) {
            if ($disputeImage->getDispute() === $this) {
                $disputeImage->setDispute(null);
            }
        }

        return $this;
    }
}
