<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasModeration;

class Publication extends Model
{
    use HasFactory, HasModeration;

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

    // Ancienne relation pour compatibilité (peut être supprimée)
    public function auteur()
    {
        return $this->belongsTo(Auteur::class);
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

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->slug = now()->format('Ymd') . '-' . Str::slug($model->nom ?? $model->titre);
        });
    }
}


