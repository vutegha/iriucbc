<?php

namespace App\Policies;

use App\Models\Projet;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjetPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->checkPermissionTo('view_projets', 'web') ||
               $user->hasAnyRole(['admin', 'moderator', 'contributeur'], 'web');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Projet $projet): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->checkPermissionTo('view_projets', 'web') ||
               $user->hasAnyRole(['admin', 'moderator', 'contributeur'], 'web');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->checkPermissionTo('create_projets', 'web') ||
               $user->hasAnyRole(['admin', 'contributeur'], 'web');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Projet $projet): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->checkPermissionTo('update_projets', 'web') ||
               $user->hasAnyRole(['admin'], 'web');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Projet $projet): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->checkPermissionTo('delete_projets', 'web') ||
               $user->hasAnyRole(['admin'], 'web');
    }

    /**
     * Determine whether the user can moderate the model.
     */
    public function moderate(User $user, ?Projet $projet = null): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->checkPermissionTo('moderate_projets', 'web') ||
               $user->hasAnyRole(['admin', 'moderator'], 'web');
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user, Projet $projet): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->checkPermissionTo('publish_projets', 'web') ||
               $user->hasAnyRole(['admin', 'moderator'], 'web');
    }

    /**
     * Determine whether the user can unpublish the model.
     */
    public function unpublish(User $user, Projet $projet): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->checkPermissionTo('unpublish_projets', 'web') ||
               $user->hasAnyRole(['admin', 'moderator'], 'web');
    }

                }