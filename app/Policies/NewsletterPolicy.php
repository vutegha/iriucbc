<?php

namespace App\Policies;

use App\Models\Newsletter;
use App\Models\User;
use App\Policies\Concerns\ChecksAdminAbility;

/**
 * The subscriber list is personal data: administrators, or users granted
 * the newsletter permissions, only.
 */
class NewsletterPolicy
{
    use ChecksAdminAbility;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'view_newsletters')
            || $user->checkPermissionTo('manage_newsletter');
    }

    public function view(User $user, Newsletter $newsletter): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'manage_newsletter');
    }

    public function update(User $user, Newsletter $newsletter): bool
    {
        return $this->allows($user, 'manage_newsletter');
    }

    public function delete(User $user, Newsletter $newsletter): bool
    {
        return $this->allows($user, 'manage_newsletter');
    }

    public function export(User $user): bool
    {
        return $this->allows($user, 'manage_newsletter');
    }
}
