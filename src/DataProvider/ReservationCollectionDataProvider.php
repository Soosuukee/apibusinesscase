<?php

namespace App\DataProvider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Symfony\Bundle\SecurityBundle\Security;

final class ReservationCollectionDataProvider implements ProviderInterface
{
    public function __construct(
        private ReservationRepository $reservationRepository,
        private Security $security
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable
    {
        $user = $this->security->getUser();

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return $this->reservationRepository->findAll();
        }

        if ($this->security->isGranted('ROLE_OWNER')) {
            return $this->reservationRepository->findByOwner($user);
        }

        return $this->reservationRepository->findBy(['client' => $user]);
    }
}
