<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasModeration;

class Rapport extends Model
{
    use HasFactory, HasModeration;

    protected $fillable = [
        'titre', 'description', 'fichier', 'date_publication', 'categorie_id',
        'is_published', 'published_at', 'published_by', 'moderation_comment'
    ];

    protected $casts = [
        'date_publication' => 'date',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }
    protected static function booted()
{
    static::creating(function ($model) {
        $model->slug = now()->format('Ymd') . '-' .Str::slug($model->nom ?? $model->titre);
    });
}
}


