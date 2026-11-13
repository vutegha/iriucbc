<?php

namespace App\Policies;

use App\Models\JobOffer;
use App\Models\User;
use App\Policies\Concerns\ChecksAdminAbility;

class JobOfferPolicy
{
    use ChecksAdminAbility;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'view_job_offers', ['admin', 'moderator']);
    }

    public function view(User $user, JobOffer $jobOffer): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'create_job_offers');
    }

    public function update(User $user, JobOffer $jobOffer): bool
    {
        return $this->allows($user, 'update_job_offers');
    }

    public function delete(User $user, JobOffer $jobOffer): bool
    {
        return $this->allows($user, 'delete_job_offers');
    }
}
