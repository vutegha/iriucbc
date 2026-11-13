<?php

namespace App\Policies;

use App\Models\JobApplication;
use App\Models\User;
use App\Policies\Concerns\ChecksAdminAbility;

/**
 * Job applications hold personal data (CVs, contact details), so access is
 * limited to administrators and to users granted the explicit permission.
 */
class JobApplicationPolicy
{
    use ChecksAdminAbility;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'view_job_applications')
            || $user->checkPermissionTo('manage_job_applications');
    }

    public function view(User $user, JobApplication $application): bool
    {
        return $this->viewAny($user);
    }

    public function updateAny(User $user): bool
    {
        return $this->allows($user, 'update_job_applications')
            || $user->checkPermissionTo('manage_job_applications');
    }

    public function update(User $user, JobApplication $application): bool
    {
        return $this->updateAny($user);
    }

    public function delete(User $user, JobApplication $application): bool
    {
        return $this->allows($user, 'delete_job_applications')
            || $user->checkPermissionTo('manage_job_applications');
    }

    public function export(User $user): bool
    {
        return $this->allows($user, 'export_job_applications')
            || $user->checkPermissionTo('manage_job_applications');
    }
}
