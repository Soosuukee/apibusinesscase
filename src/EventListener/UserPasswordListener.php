<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPasswordListener
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof User) {
            return;
        }

        $this->encodePassword($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof User) {
            return;
        }

        if (!$this->isHashed($entity->getPassword())) {
            $this->encodePassword($entity);
        }
    }

    private function encodePassword(User $user): void
    {
        $hashed = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashed);
    }

    private function isHashed(string $password): bool
    {
        return str_starts_with($password, '$2y$') || str_starts_with($password, '$argon2');
    }
}
