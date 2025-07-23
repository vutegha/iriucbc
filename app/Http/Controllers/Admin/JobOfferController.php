<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobOffer;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobOfferController extends Controller
{
    /**
     * Afficher la liste des offres d'emploi
     */
    public function index()
    {
        try {
            $jobOffers = JobOffer::orderBy('created_at', 'desc')->paginate(15);

            return view('admin.job-offers.index', compact('jobOffers'));
        } catch (\Exception $e) {
            \Log::error('Erreur dans JobOfferController@index: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement des offres: ' . $e->getMessage());
        }
    }

    /**
     * Afficher le formulaire de création d'une offre
     */
    public function create()
    {
        return view('admin.job-offers.create');
    }

    /**
     * Sauvegarder une nouvelle offre d'emploi
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'type' => 'required|in:full-time,part-time,contract,internship',
            'location' => 'required|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'deadline' => 'required|date|after:today',
            'positions_available' => 'required|integer|min:1',
            'source' => 'required|in:internal,partner',
            'partner_name' => 'required_if:source,partner|nullable|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,active,paused,closed',
            'criteria' => 'array',
            'criteria.*.question' => 'required|string|max:500',
            'criteria.*.type' => 'required|in:text,textarea,select,radio,checkbox',
            'criteria.*.required' => 'boolean',
            'criteria.*.options' => 'array',
        ]);

        // Traitement des critères
        if ($request->has('criteria')) {
            $validated['criteria'] = array_map(function($criterion) {
                return [
                    'question' => $criterion['question'],
                    'type' => $criterion['type'],
                    'required' => isset($criterion['required']) ? true : false,
                    'options' => $criterion['options'] ?? []
                ];
            }, $request->criteria);
        }

        JobOffer::create($validated);

        return redirect()->route('admin.job-offers.index')
            ->with('success', 'Offre d\'emploi créée avec succès.');
    }

    /**
     * Afficher une offre d'emploi spécifique
     */
    public function show(JobOffer $jobOffer)
    {
        $jobOffer->load(['applications' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        $stats = [
            'total_applications' => $jobOffer->applications->count(),
            'pending' => $jobOffer->applications->where('status', 'pending')->count(),
            'reviewed' => $jobOffer->applications->where('status', 'reviewed')->count(),
            'shortlisted' => $jobOffer->applications->where('status', 'shortlisted')->count(),
            'accepted' => $jobOffer->applications->where('status', 'accepted')->count(),
            'rejected' => $jobOffer->applications->where('status', 'rejected')->count(),
        ];

        return view('admin.job-offers.show', compact('jobOffer', 'stats'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(JobOffer $jobOffer)
    {
        return view('admin.job-offers.edit', compact('jobOffer'));
    }

    /**
     * Mettre à jour une offre d'emploi
     */
    public function update(Request $request, JobOffer $jobOffer)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'type' => 'required|in:full-time,part-time,contract,internship',
            'location' => 'required|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'deadline' => 'required|date',
            'positions_available' => 'required|integer|min:1',
            'source' => 'required|in:internal,partner',
            'partner_name' => 'required_if:source,partner|nullable|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,active,paused,closed',
            'criteria' => 'array',
        ]);

        $jobOffer->update($validated);

        return redirect()->route('admin.job-offers.show', $jobOffer)
            ->with('success', 'Offre d\'emploi mise à jour avec succès.');
    }

    /**
     * Supprimer une offre d'emploi
     */
    public function destroy(JobOffer $jobOffer)
    {
        // Supprimer les fichiers associés aux candidatures
        foreach ($jobOffer->applications as $application) {
            if ($application->cv_path) {
                Storage::disk('public')->delete($application->cv_path);
            }
            if ($application->portfolio_path) {
                Storage::disk('public')->delete($application->portfolio_path);
            }
        }

        $jobOffer->delete();

        return redirect()->route('admin.job-offers.index')
            ->with('success', 'Offre d\'emploi supprimée avec succès.');
    }

    /**
     * Dupliquer une offre d'emploi
     */
    public function duplicate(JobOffer $jobOffer)
    {
        $newJobOffer = $jobOffer->replicate();
        $newJobOffer->title = $jobOffer->title . ' (Copie)';
        $newJobOffer->status = 'draft';
        $newJobOffer->deadline = now()->addMonths(1);
        $newJobOffer->applications_count = 0;
        $newJobOffer->views_count = 0;
        $newJobOffer->save();

        return redirect()->route('admin.job-offers.edit', $newJobOffer)
            ->with('success', 'Offre d\'emploi dupliquée avec succès.');
    }

    /**
     * Changer le statut d'une offre
     */
    public function changeStatus(Request $request, JobOffer $jobOffer)
    {
        $request->validate([
            'status' => 'required|in:draft,active,paused,closed'
        ]);

        $jobOffer->update(['status' => $request->status]);

        return back()->with('success', 'Statut de l\'offre mis à jour avec succès.');
    }

    /**
     * Marquer une offre comme vedette
     */
    public function toggleFeatured(JobOffer $jobOffer)
    {
        $jobOffer->update(['is_featured' => !$jobOffer->is_featured]);

        $message = $jobOffer->is_featured ? 'Offre marquée comme vedette.' : 'Offre retirée des vedettes.';
        
        return back()->with('success', $message);
    }

    /**
     * Statistiques des offres d'emploi
     */
    public function statistics()
    {
        $stats = [
            'total_offers' => JobOffer::count(),
            'active_offers' => JobOffer::active()->count(),
            'expired_offers' => JobOffer::expired()->count(),
            'total_applications' => JobApplication::count(),
            'pending_applications' => JobApplication::where('status', 'pending')->count(),
            'recent_applications' => JobApplication::where('created_at', '>=', now()->subDays(7))->count(),
            'internal_offers' => JobOffer::where('source', 'internal')->count(),
            'partner_offers' => JobOffer::where('source', 'partner')->count(),
        ];

        $monthlyApplications = JobApplication::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $popularJobTypes = JobOffer::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->orderBy('count', 'desc')
            ->pluck('count', 'type')
            ->toArray();

        return view('admin.job-offers.statistics', compact('stats', 'monthlyApplications', 'popularJobTypes'));
    }
}
