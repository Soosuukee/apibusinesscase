<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait TimestampableTrait
{
    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups([
        'announcement:read',
        'announcement:read:item',
        'dispute:read',
        'dispute:read:item',
        'dispute_image:read',
        'dispute_image:read:item',
        'image:read',
        'image:read:item'
    ])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups([
        'announcement:read',
        'announcement:read:item',
        'dispute:read',
        'dispute:read:item',
        'dispute_image:read',
        'dispute_image:read:item',
        'image:read',
        'image:read:item'
    ])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups([
        'announcement:read',
        'announcement:read:item',
        'dispute:read',
        'dispute:read:item',
        'dispute_image:read',
        'dispute_image:read:item',
        'image:read',
        'image:read:item'
    ])]
    private ?\DateTimeImmutable $uploadedAt = null;

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getUploadedAt(): ?\DateTimeImmutable
    {
        return $this->uploadedAt;
    }

    public function setUploadedAt(?\DateTimeImmutable $uploadedAt): static
    {
        $this->uploadedAt = $uploadedAt;
        return $this;
    }
}
