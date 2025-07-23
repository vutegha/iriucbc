<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Evenement extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'resume',
        'description',
        'image',
        'date_debut',
        'date_fin',
        'lieu',
        'rapport_url',
        'is_published',
        'published_at',
        'published_by',
        'moderation_comment'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date'
    ];

    // Scopes
    public function scopeAVenir($query)
    {
        return $query->where('date_debut', '>=', now()->toDateString());
    }

    public function scopePasse($query)
    {
        return $query->where('date_fin', '<', now()->toDateString());
    }

    public function scopeEnCours($query)
    {
        return $query->where('date_debut', '<=', now()->toDateString())
                    ->where('date_fin', '>=', now()->toDateString());
    }

    // Accesseurs
    public function getEstPasseAttribute()
    {
        return $this->date_fin < now()->toDateString();
    }

    public function getEstAVenirAttribute()
    {
        return $this->date_debut > now()->toDateString();
    }

    public function getEstEnCoursAttribute()
    {
        return $this->date_debut <= now()->toDateString() && $this->date_fin >= now()->toDateString();
    }

    public function getStatutAttribute()
    {
        if ($this->est_passe) {
            return 'passé';
        } elseif ($this->est_en_cours) {
            return 'en cours';
        } else {
            return 'à venir';
        }
    }
}
