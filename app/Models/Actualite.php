<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasModeration;

class Actualite extends Model
{
    use HasFactory, HasModeration;

    protected $fillable = [
        'titre', 'resume', 'texte', 'image', 'en_vedette', 'a_la_une', 'service_id',
        'is_published', 'published_at', 'published_by', 'moderation_comment'
    ];

    protected $casts = [
        'en_vedette' => 'boolean',
        'a_la_une' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];
    
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->slug = now()->format('Ymd') . '-' . Str::slug($model->nom ?? $model->titre);
        });
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

}




