<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasModeration;
use App\Models\User;

class Projet extends Model
{
    use HasFactory, HasModeration;

    protected $fillable = [
        'nom',
        'slug',
        'description',
        'resume',
        'image',
        'service_id',
        'date_debut',
        'date_fin',
        'etat',
        'beneficiaires_hommes',
        'beneficiaires_femmes',
        'beneficiaires_enfants',
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
        'beneficiaires_enfants' => 'integer',
        'beneficiaires_total' => 'integer',
        'budget' => 'decimal:2',
    ];

    /**
     * Mutator pour calculer automatiquement le total des bénéficiaires
     */
    public function setBeneficiairesHommesAttribute($value)
    {
        $this->attributes['beneficiaires_hommes'] = $value;
        $this->calculateBeneficiairesTotal();
    }

    public function setBeneficiairesFemmesAttribute($value)
    {
        $this->attributes['beneficiaires_femmes'] = $value;
        $this->calculateBeneficiairesTotal();
    }

    public function setBeneficiairesEnfantsAttribute($value)
    {
        $this->attributes['beneficiaires_enfants'] = $value;
        $this->calculateBeneficiairesTotal();
    }

    protected function calculateBeneficiairesTotal()
    {
        $hommes = (int) ($this->attributes['beneficiaires_hommes'] ?? 0);
        $femmes = (int) ($this->attributes['beneficiaires_femmes'] ?? 0);
        $enfants = (int) ($this->attributes['beneficiaires_enfants'] ?? 0);
        
        $this->attributes['beneficiaires_total'] = $hommes + $femmes + $enfants;
    }

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
        'beneficiaires_enfants' => 'nullable|integer|min:0',
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

    public function publishedBy()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    /**
     * Calcule la durée du projet en mois
     */
    public function getDureeEnMoisAttribute()
    {
        if (!$this->date_debut || !$this->date_fin) {
            return null;
        }

        $debut = $this->date_debut;
        $fin = $this->date_fin;
        
        // Calcul de la différence en mois
        $mois = $debut->diffInMonths($fin);
        
        // Arrondir à 1 décimale pour plus de précision
        $jours = $debut->diffInDays($fin);
        $moisDecimal = round($jours / 30.44, 1); // Utilisation de la moyenne mensuelle
        
        return $moisDecimal;
    }

    /**
     * Retourne la durée formatée en texte
     */
    public function getDureeFormateeAttribute()
    {
        $duree = $this->duree_en_mois;
        
        if ($duree === null) {
            return 'Non définie';
        }
        
        if ($duree < 1) {
            $jours = round($duree * 30.44);
            return $jours . ' jour' . ($jours > 1 ? 's' : '');
        }
        
        if ($duree == 1) {
            return '1 mois';
        }
        
        if ($duree < 12) {
            return number_format($duree, 1) . ' mois';
        }
        
        $annees = floor($duree / 12);
        $moisRestants = $duree % 12;
        
        $texte = $annees . ' an' . ($annees > 1 ? 's' : '');
        if ($moisRestants > 0) {
            $texte .= ' et ' . round($moisRestants, 1) . ' mois';
        }
        
        return $texte;
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
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Boot : génération automatique du slug si non défini et calcul automatique du total bénéficiaires
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->slug = now()->format('Ymd') . '-' . Str::slug($model->nom);
            
            // Calcul automatique du total des bénéficiaires
            $model->beneficiaires_total = ($model->beneficiaires_hommes ?? 0) + ($model->beneficiaires_femmes ?? 0);
        });

        static::updating(function ($model) {
            // Calcul automatique du total des bénéficiaires lors des mises à jour
            $model->beneficiaires_total = ($model->beneficiaires_hommes ?? 0) + ($model->beneficiaires_femmes ?? 0);
        });
    }
}
