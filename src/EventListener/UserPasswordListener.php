<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPasswordListener
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof User) {
            return;
        }

        $this->hash($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof User) {
            return;
        }

        if (!$this->isHashed($entity->getPassword())) {
            $this->hash($entity);
        }
    }

    private function hash(User $user): void
    {
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $user->getPassword())
        );
    }

    private function isHashed(string $password): bool
    {
        return str_starts_with($password, '$2y$') || str_starts_with($password, '$argon2');
    }
}
