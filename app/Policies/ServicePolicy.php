<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ServicePolicy
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
        
        return $user->checkPermissionTo('view_services', 'web') ||
               $user->hasAnyRole(['admin', 'moderator', 'contributeur'], 'web');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Service $service): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->checkPermissionTo('view_services', 'web') ||
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
        
        return $user->checkPermissionTo('create_services', 'web') ||
               $user->hasAnyRole(['admin', 'contributeur'], 'web');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Service $service): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->checkPermissionTo('update_services', 'web') ||
               $user->hasAnyRole(['admin'], 'web');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Service $service): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->checkPermissionTo('delete_services', 'web') ||
               $user->hasAnyRole(['admin'], 'web');
    }

    /**
     * Determine whether the user can moderate the model.
     */
    public function moderate(User $user, ?Service $service = null): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->checkPermissionTo('moderate_services', 'web') ||
               $user->hasAnyRole(['admin', 'moderator'], 'web');
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user, Service $service): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->checkPermissionTo('publish_services', 'web') ||
               $user->hasAnyRole(['admin', 'moderator'], 'web');
    }

    /**
     * Determine whether the user can unpublish the model.
     */
    public function unpublish(User $user, Service $service): bool
    {
        return $this->publish($user, $service);
    }



                }