<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = ['titre', 'resume', 'fichier_pdf', 'auteur_id', 'categorie_id','citation', 'en_vedette', 'a_la_une'];

    public static function rules()
    {
        return [
            'titre' => 'required|string|max:255',
            'resume' => 'required|string',
            'slug' => 'nullable|string|max:255|unique:publication,slug',
            'fichier_pdf' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,odt,odp|max:40240',
            'auteur_id' => 'required|exists:auteurs,id',
            'categorie_id' => 'required|exists:categories,id',
            'citation' => 'nullable|string|max:255',
            'en_vedette' => 'boolean',
            'a_la_une' => 'boolean',
        ];
    }

    public function auteur()
    {
        return $this->belongsTo(Auteur::class);
    }

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


