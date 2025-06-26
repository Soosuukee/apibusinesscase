<?php

namespace App\Enum;

enum ReservationStatus: string
{
    case PENDING = 'pending';       // Réservation créée mais pas encore confirmée
    case CONFIRMED = 'confirmed';   // Réservation acceptée
    case CANCELLED = 'cancelled';   // Réservation annulée
    case REJECTED = 'rejected';    // Séjour terminé
    case EXPIRED = 'expired';         // Non traitée dans les temps
    case CHECKED_IN = 'checked_in';   // Client arrivé
    case CHECKED_OUT = 'checked_out'; // Client parti
    case COMPLETED = 'completed'; // Refusée explicitement par l’hôte
}
