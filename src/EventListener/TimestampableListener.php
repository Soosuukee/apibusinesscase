<?php

namespace App\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\Traits\TimestampableTrait;

class TimestampableListener
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!in_array(TimestampableTrait::class, class_uses($entity))) {
            return;
        }

        $now = new \DateTimeImmutable();

        if (method_exists($entity, 'setCreatedAt')) {
            $entity->setCreatedAt($now);
        }

        if (method_exists($entity, 'setUploadedAt')) {
            $entity->setUploadedAt($now);
        }

        if (method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt($now);
        }
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!in_array(TimestampableTrait::class, class_uses($entity))) {
            return;
        }

        if (method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt(new \DateTimeImmutable());
        }
    }
}
