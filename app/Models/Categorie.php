<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description'];

    public function actualites()
    {
        return $this->hasMany(Actualite::class);
    }
    public function publication()
    {
        return $this->hasMany(Publication::class);
    }
}
