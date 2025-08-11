<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasModeration;
use App\Traits\NotifiesNewsletterSubscribers;

class Actualite extends Model
{
    use HasFactory, HasModeration, NotifiesNewsletterSubscribers;

    protected $fillable = [
        'titre', 'resume', 'texte', 'image', 'en_vedette', 'a_la_une', 'service_id',
        'is_published', 'published_at', 'published_by', 'moderation_comment', 'slug'
    ];

    protected $casts = [
        'en_vedette' => 'boolean',
        'a_la_une' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    protected static function booted()
    {
        static::creating(function ($model) {
            // Vérifier que le titre existe
            if (empty($model->titre)) {
                throw new \InvalidArgumentException('Le titre est requis pour créer une actualité.');
            }
            
            // Générer le slug
            $model->slug = now()->format('Ymd') . '-' . Str::slug($model->nom ?? $model->titre);
        });
        
        static::updating(function ($model) {
            // Régénérer le slug si le titre a changé et qu'il n'y a pas déjà un slug
            if ($model->isDirty('titre') && !empty($model->titre) && empty($model->slug)) {
                $model->slug = now()->format('Ymd') . '-' . Str::slug($model->nom ?? $model->titre);
            }
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Obtenir la date de création formatée de manière sécurisée
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('d/m/Y à H:i') : 'Date non disponible';
    }

    /**
     * Obtenir la date de modification formatée de manière sécurisée
     */
    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at ? $this->updated_at->format('d/m/Y à H:i') : 'Date non disponible';
    }

    /**
     * Obtenir la date de création relative de manière sécurisée
     */
    public function getCreatedAtForHumansAttribute()
    {
        return $this->created_at ? $this->created_at->diffForHumans() : 'Date inconnue';
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auteur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Détermine le type de contenu pour la newsletter
     */
    public function getNewsletterContentType(): ?string
    {
        return 'actualites';
    }

}




