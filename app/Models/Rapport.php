<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Rapport extends Model
{
    use HasFactory;

    protected $fillable = ['titre', 'description', 'fichier', 'date_publication', 'categorie_id'];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }
    protected static function booted()
{
    static::creating(function ($model) {
        $model->slug = Str::slug($model->nom ?? $model->titre);
    });
}
}


