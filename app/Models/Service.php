<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description', 'icone'];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->slug = Str::slug($model->nom ?? $model->titre);
        });
    }
}


