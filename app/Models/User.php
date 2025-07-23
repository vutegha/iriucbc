<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Les rôles de l'utilisateur
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**
     * Vérifier si l'utilisateur a un rôle spécifique
     */
    public function hasRole(string $role): bool
    {
        return $this->roles->contains('name', $role);
    }

    /**
     * Vérifier si l'utilisateur a au moins un des rôles spécifiés
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles->whereIn('name', $roles)->isNotEmpty();
    }

    /**
     * Vérifier si l'utilisateur a une permission spécifique
     */
    public function hasPermissionTo(string $permission): bool
    {
        return $this->roles->pluck('permissions')->flatten()->contains('name', $permission);
    }

    /**
     * Vérifier si l'utilisateur peut modérer le contenu
     */
    public function canModerate(): bool
    {
        // Utiliser Spatie Laravel Permission
        return $this->hasPermissionTo('moderate_content') || 
               $this->hasAnyRole(['super-admin', 'admin', 'moderator']);
    }

    /**
     * Vérifier si l'utilisateur peut accéder à l'interface admin
     */
    public function canAccessAdmin(): bool
    {
        return $this->hasPermissionTo('view_admin') ||
               $this->hasAnyRole(['super-admin', 'admin', 'moderator', 'editor', 'contributor']);
    }

    /**
     * Vérifier si l'utilisateur peut gérer les utilisateurs
     */
    public function canManageUsers(): bool
    {
        return $this->hasPermissionTo('manage_users') ||
               $this->hasAnyRole(['super-admin', 'admin']);
    }

    /**
     * Assigner un rôle à l'utilisateur
     */
    public function assignRole(string $role): void
    {
        $roleModel = Role::where('name', $role)->first();
        if ($roleModel && !$this->hasRole($role)) {
            $this->roles()->attach($roleModel);
        }
    }

    /**
     * Retirer un rôle de l'utilisateur
     */
    public function removeRole(string $role): void
    {
        $roleModel = Role::where('name', $role)->first();
        if ($roleModel && $this->hasRole($role)) {
            $this->roles()->detach($roleModel);
        }
    }
}
