<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasModeration;

class Service extends Model
{
    use HasFactory, HasModeration;

    protected $fillable = [
        'nom', 'nom_menu', 'resume', 'description', 'image',
        'is_published', 'show_in_menu', 'published_at', 'published_by', 'moderation_comment'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'show_in_menu' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->slug = now()->format('Ymd') . '-' .Str::slug($model->nom ?? $model->titre);
        });
    }
    public function projets()
    {
        return $this->hasMany(Projet::class);
    }

    public function actualites()
    {
        return $this->hasMany(Actualite::class);
    }

    /**
     * Scope pour récupérer les services affichés dans le menu
     */
    public function scopeInMenu($query)
    {
        return $query->where('show_in_menu', true)->where('is_published', true);
    }

    /**
     * Activer/Désactiver l'affichage dans le menu
     */
    public function toggleMenu()
    {
        $this->update(['show_in_menu' => !$this->show_in_menu]);
        return $this;
    }

    /**
     * Afficher dans le menu
     */
    public function showInMenu()
    {
        $this->update(['show_in_menu' => true]);
        return $this;
    }

    /**
     * Masquer du menu
     */
    public function hideFromMenu()
    {
        $this->update(['show_in_menu' => false]);
        return $this;
    }
    
    public function getRouteKeyName()
    {
        return 'slug';
    }
}


