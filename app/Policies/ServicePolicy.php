<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any services.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->canViewServices();
    }

    /**
     * Determine whether the user can view the service.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Service  $service
     * @return mixed
     */
    public function view(User $user, Service $service)
    {
        return $user->canViewServices();
    }

    /**
     * Determine whether the user can create services.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->canCreateServices();
    }

    /**
     * Determine whether the user can update the service.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Service  $service
     * @return mixed
     */
    public function update(User $user, Service $service)
    {
        return $user->canUpdateServices();
    }

    /**
     * Determine whether the user can delete the service.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Service  $service
     * @return mixed
     */
    public function delete(User $user, Service $service)
    {
        return $user->canDeleteServices();
    }

    /**
     * Determine whether the user can moderate the service.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Service  $service
     * @return mixed
     */
    public function moderate(User $user, Service $service)
    {
        return $user->hasPermissionTo('moderate services') || 
               $user->hasAnyRole(['admin', 'super-admin', 'moderateur']);
    }

    /**
     * Determine whether the user can publish the service.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Service  $service
     * @return mixed
     */
    public function publish(User $user, Service $service)
    {
        return $user->hasPermissionTo('publish services') || 
               $user->hasAnyRole(['admin', 'super-admin', 'moderateur']);
    }

    /**
     * Determine whether the user can unpublish the service.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Service  $service
     * @return mixed
     */
    public function unpublish(User $user, Service $service)
    {
        return $user->hasPermissionTo('unpublish services') || 
               $user->hasAnyRole(['admin', 'super-admin', 'moderateur']);
    }
}
