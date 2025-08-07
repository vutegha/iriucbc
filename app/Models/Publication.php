<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasModeration;
use App\Traits\NotifiesNewsletterSubscribers;

class Publication extends Model
{
    use HasFactory, HasModeration, NotifiesNewsletterSubscribers;

    protected $fillable = [
        'titre', 'resume', 'fichier_pdf', 'categorie_id', 'citation', 'en_vedette', 'a_la_une',
        'is_published', 'published_at', 'published_by', 'moderation_comment'
    ];

    protected $casts = [
        'en_vedette' => 'boolean',
        'a_la_une' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public static function rules()
    {
        return [
            'titre' => 'required|string|max:255',
            'resume' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:publication,slug',
            'fichier_pdf' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,odt,odp|max:40240',
            'auteurs' => 'required|array',
            'auteurs.*' => 'exists:auteurs,id',
            'categorie_id' => 'required|exists:categories,id',
            'citation' => 'nullable|string|max:255',
            'en_vedette' => 'boolean',
            'a_la_une' => 'boolean',
        ];
    }

    // Relation many-to-many avec Auteur
    public function auteurs()
    {
        return $this->belongsToMany(Auteur::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    /**
     * Accesseur pour maintenir la compatibilité avec 'title'
     */
    public function getTitleAttribute()
    {
        return $this->titre;
    }

    /**
     * Détermine le type de contenu pour la newsletter
     */
    public function getNewsletterContentType(): ?string
    {
        return 'publications';
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->slug = now()->format('Ymd') . '-' . Str::slug($model->nom ?? $model->titre);
        });
    }

    /**
     * Détermine si cette publication est un brouillon
     */
    public function isDraft()
    {
        return !$this->is_published && (
            !$this->fichier_pdf || 
            Str::startsWith($this->titre, ['Brouillon', 'Draft']) || 
            !$this->resume ||
            !$this->categorie_id
        );
    }

    /**
     * Scope pour les brouillons
     */
    public function scopeDraft($query)
    {
        return $query->where('is_published', false)
                    ->where(function($subQuery) {
                        $subQuery->whereNull('fichier_pdf')
                                 ->orWhere('titre', 'like', 'Brouillon%')
                                 ->orWhere('titre', 'like', 'Draft%')
                                 ->orWhereNull('resume')
                                 ->orWhereNull('categorie_id');
                    });
    }

    /**
     * Obtient l'URL de la miniature du PDF (première page)
     * Note: Cette méthode est désormais obsolète car nous utilisons PDF.js côté client
     * Conservée pour la compatibilité
     */
    public function getThumbnailUrl()
    {
        // Les miniatures sont maintenant générées côté client avec PDF.js
        // Cette méthode retourne null pour forcer l'utilisation de PDF.js
        return null;
    }

    /**
     * Vérifie si une miniature existe pour cette publication
     * Note: Cette méthode est désormais obsolète car nous utilisons PDF.js côté client
     * Conservée pour la compatibilité
     */
    public function hasThumbnail()
    {
        // Les miniatures sont maintenant générées côté client avec PDF.js
        return false;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}


