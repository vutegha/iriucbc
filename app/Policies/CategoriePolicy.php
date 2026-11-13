<?php

namespace App\Policies;

use App\Models\Categorie;
use App\Models\User;
use App\Policies\Concerns\ChecksAdminAbility;

class CategoriePolicy
{
    use ChecksAdminAbility;

    public function viewAny(User $user): bool
    {
        return $this->allows($user, 'view_categories', ['admin', 'moderator']);
    }

    public function create(User $user): bool
    {
        return $this->allows($user, 'create_categories', ['admin', 'moderator']);
    }

    public function update(User $user, Categorie $categorie): bool
    {
        return $this->allows($user, 'update_categories');
    }

    public function delete(User $user, Categorie $categorie): bool
    {
        return $this->allows($user, 'delete_categories');
    }
}
