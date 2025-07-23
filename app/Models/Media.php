<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'medias', // Chemin de stockage du fichier
        'titre',
        'description', // Description détaillée du média
        'type', // Type de média (image, vidéo, etc.)
        'projet_id', // Lien vers le projet associé
        

    ];

    /**
     * Règles de validation pour le modèle Media
     */
    public static $rules = [
        'type' => 'nullable|string|max:255', // Exemple : image, vidéo, etc.
        'titre' => 'nullable|string|max:255',
        'medias' => 'nullable|file|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg,video/mp4,video/quicktime,video/webm|max:40480',
        'projet_id' => 'nullable|exists:projets,id', // Lien vers le projet associé

    ];

        // Optionnel : accès formaté à l'URL publique
    public function getPublicUrlAttribute()
    {
        return asset('storage/' . $this->medias);
    }

    // Optionnel : retour boolean sur le type
    public function isImage()
    {
        return $this->type === 'image';
    }

    public function isVideo()
    {
        return $this->type === 'video';
    }

    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }
    
}
