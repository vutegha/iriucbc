<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class JobOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'type', 'location', 'department',
        'source', 'partner_name', 'status', 'application_deadline',
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
}
