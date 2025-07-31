<?php

namespace App\Policies;

use App\Models\Publication;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PublicationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Tous les utilisateurs authentifiés peuvent voir la liste
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Publication $publication): bool
    {
        // Tous les utilisateurs authentifiés peuvent voir les détails
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Tous les utilisateurs authentifiés peuvent créer des publications
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Publication $publication): bool
    {
        // Tous les utilisateurs authentifiés peuvent modifier (ou seulement les modérateurs selon vos besoins)
        return true; // ou $user->canModerate() pour être plus restrictif
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Publication $publication): bool
    {
        // Seuls les modérateurs peuvent supprimer
        return $user->canModerate();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Publication $publication): bool
    {
        // Seuls les modérateurs peuvent restaurer
        return $user->canModerate();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Publication $publication): bool
    {
        // Seuls les modérateurs peuvent supprimer définitivement
        return $user->canModerate();
    }

    /**
     * Determine whether the user can moderate the publication.
     */
    public function moderate(User $user, Publication $publication): bool
    {
        // Seuls les modérateurs peuvent modérer (publier/dépublier)
        return $user->canModerate();
    }

    /**
     * Determine whether the user can publish the publication.
     */
    public function publish(User $user, Publication $publication): bool
    {
        // Seuls les modérateurs peuvent publier
        return $user->canModerate();
    }

    /**
     * Determine whether the user can unpublish the publication.
     */
    public function unpublish(User $user, Publication $publication): bool
    {
        // Seuls les modérateurs peuvent dépublier
        return $user->canModerate();
    }
}
