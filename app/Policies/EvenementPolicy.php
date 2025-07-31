<?php

namespace App\Policies;

use App\Models\Evenement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EvenementPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any evenements.
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view evenements') || 
               $user->hasAnyRole(['admin', 'super-admin', 'moderateur']);
    }

    /**
     * Determine whether the user can view the evenement.
     */
    public function view(User $user, Evenement $evenement)
    {
        return $user->hasPermissionTo('view evenements') || 
               $user->hasAnyRole(['admin', 'super-admin', 'moderateur']);
    }

    /**
     * Determine whether the user can create evenements.
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create evenements') || 
               $user->hasAnyRole(['admin', 'super-admin']);
    }

    /**
     * Determine whether the user can update the evenement.
     */
    public function update(User $user, Evenement $evenement)
    {
        return $user->hasPermissionTo('edit evenements') || 
               $user->hasAnyRole(['admin', 'super-admin']);
    }

    /**
     * Determine whether the user can delete the evenement.
     */
    public function delete(User $user, Evenement $evenement)
    {
        return $user->hasPermissionTo('delete evenements') || 
               $user->hasAnyRole(['admin', 'super-admin']);
    }

    /**
     * Determine whether the user can publish the evenement.
     */
    public function publish(User $user, Evenement $evenement)
    {
        return $user->hasPermissionTo('publish evenements') || 
               $user->hasAnyRole(['admin', 'super-admin', 'moderateur']);
    }

    /**
     * Determine whether the user can unpublish the evenement.
     */
    public function unpublish(User $user, Evenement $evenement)
    {
        return $user->hasPermissionTo('unpublish evenements') || 
               $user->hasAnyRole(['admin', 'super-admin', 'moderateur']);
    }

    /**
     * Determine whether the user can moderate the evenement.
     */
    public function moderate(User $user, Evenement $evenement)
    {
        return $user->hasPermissionTo('moderate evenements') || 
               $user->hasAnyRole(['admin', 'super-admin', 'moderateur']);
    }
}
