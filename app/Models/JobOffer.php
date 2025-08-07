<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class JobOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'type', 'location', 'department',
        'source', 'partner_name', 'partner_logo', 'status', 'application_deadline',
        'requirements', 'criteria', 'benefits', 'salary_min', 'salary_max',
        'salary_negotiable', 'positions_available', 'contact_email',
        'contact_phone', 'document_appel_offre', 'document_appel_offre_nom',
        'is_featured', 'views_count', 'applications_count'
    ];

    protected $casts = [
        'requirements' => 'array',
        'criteria' => 'array',
        'application_deadline' => 'date',
        'salary_negotiable' => 'boolean',
        'is_featured' => 'boolean',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
    ];

    // Relations
    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where(function($q) {
                        $q->whereNull('application_deadline')
                          ->orWhere('application_deadline', '>=', now());
                    });
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'active')
                    ->whereNotNull('application_deadline')
                    ->where('application_deadline', '<', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeBySource($query, $source)
    {
        return $query->where('source', $source);
    }

    // Accessors
    public function getIsExpiredAttribute()
    {
        return $this->application_deadline && $this->application_deadline->isPast();
    }

    public function getFormattedSalaryAttribute()
    {
        if ($this->salary_min && $this->salary_max) {
            return number_format($this->salary_min) . ' - ' . number_format($this->salary_max) . ' USD';
        } elseif ($this->salary_min) {
            return 'À partir de ' . number_format($this->salary_min) . ' USD';
        } elseif ($this->salary_negotiable) {
            return 'Salaire négociable';
        }
        return 'Non spécifié';
    }

    public function getDaysUntilDeadlineAttribute()
    {
        if (!$this->application_deadline) {
            return null;
        }
        return now()->diffInDays($this->application_deadline, false);
    }

    // Accesseur pour compatibilité avec la vue
    public function getDeadlineAttribute()
    {
        return $this->application_deadline;
    }

    // Accesseur pour le document d'appel d'offre
    public function getDocumentAppelOffreUrlAttribute()
    {
        if ($this->document_appel_offre) {
            return asset('storage/' . $this->document_appel_offre);
        }
        return null;
    }

    public function hasDocumentAppelOffre()
    {
        return !empty($this->document_appel_offre);
    }

    // Méthodes helper pour les fichiers
    public function getDocumentAppelOffreSize()
    {
        if (!$this->document_appel_offre || !Storage::disk('public')->exists($this->document_appel_offre)) {
            return 0;
        }
        return Storage::disk('public')->size($this->document_appel_offre);
    }

    public function getDocumentAppelOffreSizeFormatted()
    {
        $size = $this->getDocumentAppelOffreSize();
        if ($size == 0) return '0 B';
        
        $units = ['B', 'KB', 'MB', 'GB'];
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return number_format($size / pow(1024, $power), 1) . ' ' . $units[$power];
    }

    public function getDocumentAppelOffreExtension()
    {
        if (!$this->document_appel_offre) return null;
        return strtoupper(pathinfo($this->document_appel_offre, PATHINFO_EXTENSION));
    }

    public function getPartnerLogoSize()
    {
        if (!$this->partner_logo || !Storage::disk('public')->exists($this->partner_logo)) {
            return 0;
        }
        return Storage::disk('public')->size($this->partner_logo);
    }

    public function getPartnerLogoSizeFormatted()
    {
        $size = $this->getPartnerLogoSize();
        if ($size == 0) return '0 B';
        
        $units = ['B', 'KB', 'MB', 'GB'];
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return number_format($size / pow(1024, $power), 1) . ' ' . $units[$power];
    }

    public function hasPartnerLogo()
    {
        return !empty($this->partner_logo);
    }

    // Methods
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function incrementApplications()
    {
        $this->increment('applications_count');
    }

    public function markAsExpired()
    {
        $this->update(['status' => 'expired']);
    }

    // Génération automatique du slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($jobOffer) {
            if (empty($jobOffer->slug)) {
                $jobOffer->slug = $jobOffer->generateUniqueSlug($jobOffer->title);
            }
        });

        static::updating(function ($jobOffer) {
            if ($jobOffer->isDirty('title') && empty($jobOffer->slug)) {
                $jobOffer->slug = $jobOffer->generateUniqueSlug($jobOffer->title);
            }
        });
    }

    /**
     * Générer un slug unique pour l'offre d'emploi
     */
    private function generateUniqueSlug($title)
    {
        $baseSlug = \Illuminate\Support\Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Trouver une offre d'emploi par son slug
     */
    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->firstOrFail();
    }

    /**
     * Obtenir l'URL de l'offre d'emploi
     */
    public function getUrlAttribute()
    {
        return route('admin.job-offers.show', $this->slug);
    }

    /**
     * Spécifier que les routes doivent utiliser le slug au lieu de l'ID
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
