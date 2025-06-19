<?php

namespace App\Enum;

enum ReservationStatus: string
{
    case PENDING = 'pending';       // Réservation créée mais pas encore confirmée
    case CONFIRMED = 'confirmed';   // Réservation acceptée
    case CANCELLED = 'cancelled';   // Réservation annulée
    case COMPLETED = 'completed';   // Séjour terminé
}
