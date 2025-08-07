<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auteur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 
        'prenom', 
        'email', 
        'telephone',
        'institution', 
        'titre_professionnel',
        'biographie', 
        'photo',
        'linkedin',
        'twitter',
        'website',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public static $rules = [
        'nom' => 'required|string|max:255',
        'prenom' => 'nullable|string|max:255',
        'email' => 'nullable|email|max:255',
        'telephone' => 'nullable|string|max:255',
        'institution' => 'nullable|string|max:255',
        'titre_professionnel' => 'nullable|string|max:255',
        'biographie' => 'nullable|string',
        'photo' => 'nullable|string|max:255',
        'linkedin' => 'nullable|url|max:255',
        'twitter' => 'nullable|url|max:255',
        'website' => 'nullable|url|max:255',
        'active' => 'boolean',
    ];

    // Relation many-to-many avec Publication
    public function publications()
    {
        return $this->belongsToMany(Publication::class);
    }
}
