<?php

namespace App\Policies;

use App\Models\Media;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MediaPolicy
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
        
        return $user->checkPermissionTo('view_medias', 'web') ||
               $user->hasAnyRole(['admin', 'moderator', 'contributeur'], 'web');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Media $media): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->checkPermissionTo('view_medias', 'web') ||
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
        
        return $user->checkPermissionTo('create_medias', 'web') ||
               $user->hasAnyRole(['admin', 'contributeur'], 'web');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Media $media): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->checkPermissionTo('update_medias', 'web') ||
               $user->hasAnyRole(['admin'], 'web');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Media $media): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->checkPermissionTo('delete_medias', 'web') ||
               $user->hasAnyRole(['admin'], 'web');
    }

    /**
     * Determine whether the user can moderate the model.
     */
    public function moderate(User $user, ?Media $media = null): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->checkPermissionTo('moderate_medias', 'web') ||
               $user->hasAnyRole(['admin', 'moderator'], 'web');
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user, Media $media): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->checkPermissionTo('publish_medias', 'web') ||
               $user->hasAnyRole(['admin', 'moderator'], 'web');
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, Media $media): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->checkPermissionTo('approve_medias', 'web') ||
               $user->hasAnyRole(['admin', 'moderator'], 'web');
    }

    /**
     * Determine whether the user can reject the model.
     */
    public function reject(User $user, Media $media): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->checkPermissionTo('reject_medias', 'web') ||
               $user->hasAnyRole(['admin', 'moderator'], 'web');
    }

    /**
     * Determine whether the user can copy link of the model.
     */
    public function copyLink(User $user, Media $media): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->checkPermissionTo('download_medias', 'web') ||
               $user->hasAnyRole(['admin', 'moderator', 'contributeur'], 'web');
    }

    /**
     * Determine whether the user can download the model.
     */
    public function download(User $user, Media $media): bool
    {
        // Super-admin a tous les droits
        if ($user->hasRole('super-admin', 'web')) {
            return true;
        }
        
        return $user->checkPermissionTo('download_medias', 'web') ||
               $user->hasAnyRole(['admin', 'moderator', 'contributeur'], 'web');
    }
}