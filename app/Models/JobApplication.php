<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_offer_id', 'first_name', 'last_name', 'email', 'phone',
        'address', 'birth_date', 'gender', 'nationality', 'education',
        'experience', 'skills', 'motivation_letter', 'criteria_responses',
        'cv_path', 'portfolio_path', 'additional_documents', 'status',
        'admin_notes', 'reviewed_at', 'reviewed_by', 'score'
    ];

    protected $casts = [
        'criteria_responses' => 'array',
        'additional_documents' => 'array',
        'birth_date' => 'date',
        'reviewed_at' => 'datetime',
        'score' => 'decimal:2',
    ];

    // Relations
    public function jobOffer()
    {
        return $this->belongsTo(JobOffer::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }

    public function scopeShortlisted($query)
    {
        return $query->where('status', 'shortlisted');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'En attente',
            'reviewed' => 'Examiné',
            'shortlisted' => 'Présélectionné',
            'rejected' => 'Rejeté',
            'accepted' => 'Accepté'
        ];
        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'text-yellow-600 bg-yellow-100',
            'reviewed' => 'text-blue-600 bg-blue-100',
            'shortlisted' => 'text-purple-600 bg-purple-100',
            'rejected' => 'text-red-600 bg-red-100',
            'accepted' => 'text-green-600 bg-green-100'
        ];
        return $colors[$this->status] ?? 'text-gray-600 bg-gray-100';
    }

    // Methods
    public function markAsReviewed($reviewerId = null, $notes = null)
    {
        $this->update([
            'status' => 'reviewed',
            'reviewed_at' => now(),
            'reviewed_by' => $reviewerId,
            'admin_notes' => $notes
        ]);
    }

    public function shortlist($reviewerId = null, $notes = null)
    {
        $this->update([
            'status' => 'shortlisted',
            'reviewed_at' => now(),
            'reviewed_by' => $reviewerId,
            'admin_notes' => $notes
        ]);
    }

    public function accept($reviewerId = null, $notes = null)
    {
        $this->update([
            'status' => 'accepted',
            'reviewed_at' => now(),
            'reviewed_by' => $reviewerId,
            'admin_notes' => $notes
        ]);
    }

    public function reject($reviewerId = null, $notes = null)
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'reviewed_by' => $reviewerId,
            'admin_notes' => $notes
        ]);
    }
}
