<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Newsletter extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'nom',
        'token',
        'actif',
        'confirme_a'
    ];

    protected $casts = [
        'actif' => 'boolean',
        'confirme_a' => 'datetime',
    ];

    /**
     * Boot du modèle pour générer un token unique
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($newsletter) {
            if (empty($newsletter->token)) {
                $newsletter->token = Str::random(64);
            }
        });
    }

    /**
     * Relation avec les préférences
     */
    public function preferences()
    {
        return $this->hasMany(NewsletterPreference::class);
    }

    /**
     * Vérifie si l'abonné a une préférence pour un type donné
     */
    public function hasPreference($type)
    {
        return $this->preferences()
            ->where('type', $type)
            ->where('actif', true)
            ->exists();
    }

    /**
     * Scope pour les abonnés actifs
     */
    public function scopeActive($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Scope pour les abonnés avec une préférence spécifique
     */
    public function scopeWithPreference($query, $type)
    {
        return $query->whereHas('preferences', function ($q) use ($type) {
            $q->where('type', $type)->where('actif', true);
        });
    }
}
