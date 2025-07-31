<?php

namespace App\Policies;

use App\Models\Actualite;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ActualitePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canViewActualites();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Actualite $actualite): bool
    {
        return $user->canViewActualites();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canCreateActualites();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Actualite $actualite): bool
    {
        // L'utilisateur peut modifier ses propres actualités ou s'il a les permissions admin
        return $user->canUpdateActualites() || $actualite->auteur_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Actualite $actualite): bool
    {
        // L'utilisateur peut supprimer ses propres actualités ou s'il a les permissions admin
        return $user->canDeleteActualites() || $actualite->auteur_id === $user->id;
    }

    /**
     * Determine whether the user can moderate actualites.
     */
    public function moderate(User $user): bool
    {
        return $user->canModerate();
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user, Actualite $actualite): bool
    {
        return $user->hasPermissionTo('publish actualites') || 
               $user->hasAnyRole(['admin', 'super-admin', 'moderateur']);
    }

    /**
     * Determine whether the user can unpublish the model.
     */
    public function unpublish(User $user, Actualite $actualite): bool
    {
        return $user->hasPermissionTo('unpublish actualites') || 
               $user->hasAnyRole(['admin', 'super-admin', 'moderateur']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Actualite $actualite): bool
    {
        return $user->canDeleteActualites();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Actualite $actualite): bool
    {
        return $user->canDeleteActualites();
    }
}
