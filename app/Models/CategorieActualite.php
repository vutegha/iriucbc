<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieActualite extends Model
{
    use HasFactory;

    public function actualites() { return $this->hasMany(Actualite::class); }

}
