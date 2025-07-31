<?php

namespace App\Policies;

use App\Models\Rapport;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RapportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any rapports.
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view rapports') || 
               $user->hasAnyRole(['admin', 'super-admin', 'moderateur']);
    }

    /**
     * Determine whether the user can view the rapport.
     */
    public function view(User $user, Rapport $rapport)
    {
        return $user->hasPermissionTo('view rapports') || 
               $user->hasAnyRole(['admin', 'super-admin', 'moderateur']);
    }

    /**
     * Determine whether the user can create rapports.
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create rapports') || 
               $user->hasAnyRole(['admin', 'super-admin']);
    }

    /**
     * Determine whether the user can update the rapport.
     */
    public function update(User $user, Rapport $rapport)
    {
        return $user->hasPermissionTo('update rapports') || 
               $user->hasAnyRole(['admin', 'super-admin']);
    }

    /**
     * Determine whether the user can delete the rapport.
     */
    public function delete(User $user, Rapport $rapport)
    {
        return $user->hasPermissionTo('delete rapports') || 
               $user->hasAnyRole(['admin', 'super-admin']);
    }

    /**
     * Determine whether the user can publish the rapport.
     */
    public function publish(User $user, Rapport $rapport)
    {
        return $user->hasPermissionTo('publish rapports') || 
               $user->hasAnyRole(['admin', 'super-admin', 'moderateur']);
    }

    /**
     * Determine whether the user can unpublish the rapport.
     */
    public function unpublish(User $user, Rapport $rapport)
    {
        return $user->hasPermissionTo('unpublish rapports') || 
               $user->hasAnyRole(['admin', 'super-admin', 'moderateur']);
    }

    /**
     * Determine whether the user can moderate the rapport.
     */
    public function moderate(User $user, Rapport $rapport)
    {
        return $user->hasPermissionTo('moderate rapports') || 
               $user->hasAnyRole(['admin', 'super-admin', 'moderateur']);
    }
}
