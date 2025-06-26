<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Actualite extends Model
{
    use HasFactory;

    protected $fillable = ['titre', 'resume','texte', 'image', 'en_vedette', 'a_la_une'];
    protected static function booted()
{
    
    
    static::creating(function ($model) {
        $model->slug = Str::slug($model->nom ?? $model->titre);
    });
}

public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

}




