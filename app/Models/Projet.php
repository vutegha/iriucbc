<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasModeration;

class Projet extends Model
{
    use HasFactory, HasModeration;

    protected $fillable = [
        'nom',
        'slug',
        'description',
        'image',
        'service_id',
        'date_debut',
        'date_fin',
        'etat',
        'beneficiaires_hommes',
        'beneficiaires_femmes',
        'beneficiaires_total',
        'budget',
        'is_published',
        'published_at',
        'published_by',
        'moderation_comment'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'beneficiaires_hommes' => 'integer',
        'beneficiaires_femmes' => 'integer',
        'beneficiaires_total' => 'integer',
        'budget' => 'decimal:2',
    ];

    /**
     * Règles de validation
     */
    public static $rules = [
        'nom' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5048',
        'service_id' => 'nullable|exists:services,id',
        'resume'=>'nullable| string|max:255',
        'date_debut' => 'nullable|date',
        'date_fin' => 'nullable|date|after_or_equal:date_debut',
        'etat' => 'nullable|in:en cours,terminé,suspendu',
        'beneficiaires_hommes' => 'nullable|integer|min:0',
        'beneficiaires_femmes' => 'nullable|integer|min:0',
        'beneficiaires_total' => 'nullable|integer|min:0',
    ];

    /**
     * Relations
     */

    public function medias()
    {
        return $this->hasMany(Media::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Méthodes pour les statistiques
     */
    public static function getTotalProjets()
    {
        return self::count();
    }

    public static function getTotalBeneficiaires()
    {
        return self::sum('beneficiaires_total') ?: 0;
    }

    public static function getTotalBeneficiairesHommes()
    {
        return self::sum('beneficiaires_hommes') ?: 0;
    }

    public static function getTotalBeneficiairesFemmes()
    {
        return self::sum('beneficiaires_femmes') ?: 0;
    }

    /**
     * Boot : génération automatique du slug si non défini
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->slug = now()->format('Ymd') . '-' . Str::slug($model->nom);
        });
    }
}
