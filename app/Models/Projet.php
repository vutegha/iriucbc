<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Projet extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'slug',
        'description',
        'image',
        'date_debut',
        'date_fin',
        'etat',
    ];

    /**
     * Relation : un projet a plusieurs médias
     */

         /**
     * Règles de validation
     */
    public static $rules = [
        'nom' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5048',
        'date_debut' => 'nullable|date',
        'date_fin' => 'nullable|date|after_or_equal:date_debut',
        'etat' => 'nullable|in:en cours,terminé,suspendu',
    ];
    public function medias()
    {
        return $this->hasMany(Media::class);
    }

    /**
     * Génération automatique du slug si non défini
     */
    
        protected static function booted()
{
    static::creating(function ($model) {
        $model->slug = Str::slug($model->nom ?? $model->titre);
    });
}
    


}

