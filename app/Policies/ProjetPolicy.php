<?php

namespace App\Policies;

use App\Models\Projet;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any projects.
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view_projets') || 
               $user->hasRole(['admin', 'gestionnaire_projets']);
    }

    /**
     * Determine whether the user can view the project.
     */
    public function view(User $user, Projet $projet)
    {
        return $user->hasPermissionTo('view_projet') || 
               $user->hasRole(['admin', 'gestionnaire_projets']);
    }

    /**
     * Determine whether the user can create projects.
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create_projet') || 
               $user->hasRole(['admin', 'gestionnaire_projets']);
    }

    /**
     * Determine whether the user can update the project.
     */
    public function update(User $user, Projet $projet)
    {
        return $user->hasPermissionTo('update_projet') || 
               $user->hasRole(['admin', 'gestionnaire_projets']);
    }

    /**
     * Determine whether the user can delete the project.
     */
    public function delete(User $user, Projet $projet)
    {
        return $user->hasPermissionTo('delete_projet') || 
               $user->hasRole(['admin', 'gestionnaire_projets']);
    }

    /**
     * Determine whether the user can moderate the project.
     */
    public function moderate(User $user, ?Projet $projet = null)
    {
        return $user->hasPermissionTo('moderate_projet') || 
               $user->hasRole(['admin', 'gestionnaire_projets']);
    }

    /**
     * Determine whether the user can publish the project.
     */
    public function publish(User $user, Projet $projet)
    {
        return $user->hasPermissionTo('publish projets') || 
               $user->hasAnyRole(['admin', 'super-admin', 'gestionnaire_projets']);
    }

    /**
     * Determine whether the user can unpublish the project.
     */
    public function unpublish(User $user, Projet $projet)
    {
        return $user->hasPermissionTo('unpublish projets') || 
               $user->hasAnyRole(['admin', 'super-admin', 'gestionnaire_projets']);
    }
}
