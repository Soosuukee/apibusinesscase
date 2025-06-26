<?php

namespace App\Enum;

enum AnnouncementStatus: string
{
    case DRAFT = 'draft';           // En cours de rédaction, non visible publiquement
    case PENDING_REVIEW = 'pending_review'; // En attente de validation par un admin
    case PUBLISHED = 'published';   // Visible sur le site, ouvert à la réservation
    case UNAVAILABLE = 'unavailable'; // Temporairement indisponible (travaux, vacances…)
    case ARCHIVED = 'archived';     // Retiré définitivement (ou désactivé par l’admin)
}
