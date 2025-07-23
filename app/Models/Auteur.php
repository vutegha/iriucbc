<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auteur extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'email', 'biographie', 'photo'];

    public static $rules = [
        'nom' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:auteurs,email',
        'biographie' => 'nullable|string',
        'photo' => 'nullable|string|max:255',
    ];

    // Relation many-to-many avec Publication
    public function publications()
    {
        return $this->belongsToMany(Publication::class);
    }
}
