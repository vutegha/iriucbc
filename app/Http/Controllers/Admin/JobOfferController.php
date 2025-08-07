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
        
        $this->authorize('viewAny', JobOffer::class);
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
        
        $this->authorize('create', JobOffer::class);
return view('admin.job-offers.create');
    }

    /**
     * Sauvegarder une nouvelle offre d'emploi
     */
    public function store(Request $request)
    {
        
        $this->authorize('create', JobOffer::class);
try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'requirements' => 'required|string', // JSON string
                'benefits' => 'nullable|string',
                'type' => 'required|in:full-time,part-time,contract,internship',
                'location' => 'required|string|max:255',
                'salary_min' => 'nullable|numeric|min:0',
                'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
                'application_deadline' => 'required|date|after:today',
                'positions_available' => 'required|integer|min:1',
                'source' => 'required|in:internal,partner',
                'partner_name' => 'required_if:source,partner|nullable|string|max:255',
                'partner_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'contact_email' => 'required|email|max:255',
                'document_appel_offre' => 'nullable|file|mimes:pdf,doc,docx,odt|max:10240', // 10MB max
                'is_featured' => 'nullable|boolean',
                'status' => 'required|in:draft,active',
            ]);

            // Validation personnalisée pour les requirements (méthode STORE)
            $requirementsJson = $validated['requirements'];
            
            // Debug : log des données reçues
            \Log::info('STORE - Requirements reçus:', ['data' => $requirementsJson, 'type' => gettype($requirementsJson)]);
            
            // Traitement flexible des requirements
            if (empty($requirementsJson) || $requirementsJson === 'null') {
                return back()
                    ->withInput()
                    ->withErrors(['requirements' => 'Veuillez ajouter au moins une exigence pour le poste.']);
            }
            
            // Si c'est déjà un array (cas rare mais possible)
            if (is_array($requirementsJson)) {
                $requirementsArray = $requirementsJson;
            } else {
                // Décoder le JSON si c'est une chaîne
                $requirementsArray = json_decode($requirementsJson, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    \Log::error('STORE - Erreur JSON requirements:', [
                        'data' => $requirementsJson,
                        'error' => json_last_error_msg()
                    ]);
                    return back()
                        ->withInput()
                        ->withErrors(['requirements' => 'Format des exigences invalide. Erreur: ' . json_last_error_msg()]);
                }
            }
            
            // Vérifier que c'est bien un array
            if (!is_array($requirementsArray)) {
                return back()
                    ->withInput()
                    ->withErrors(['requirements' => 'Les exigences doivent être une liste valide.']);
            }

            // Filtrer les exigences vides et vérifier qu'il y en a au moins une
            $filteredRequirements = array_filter($requirementsArray, function($item) {
                return is_string($item) && !empty(trim($item));
            });
            
            if (empty($filteredRequirements)) {
                return back()
                    ->withInput()
                    ->withErrors(['requirements' => 'Veuillez ajouter au moins une exigence valide pour le poste.']);
            }
            
            // Le modèle JobOffer a un cast 'requirements' => 'array', donc Laravel va automatiquement
            // convertir le tableau en JSON pour la base de données
            $validated['requirements'] = array_values($filteredRequirements);

            // Mapper les valeurs du formulaire vers les valeurs de la base de données
            $typeMapping = [
                'full-time' => 'temps_plein',
                'part-time' => 'temps_partiel',
                'contract' => 'contrat',
                'internship' => 'stage'
            ];

            $sourceMapping = [
                'internal' => 'interne',
                'partner' => 'partenaire'
            ];

            // application_deadline est déjà dans le bon format

            // Mapper les valeurs
            $validated['type'] = $typeMapping[$validated['type']];
            $validated['source'] = $sourceMapping[$validated['source']];

            // Convertir is_featured en boolean
            $validated['is_featured'] = $request->has('is_featured') ? true : false;

                        // Gérer le logo du partenaire
            if ($request->hasFile('partner_logo')) {
                $logoPath = $request->file('partner_logo')->store('job-offers/partner-logos', 'public');
                $validated['partner_logo'] = $logoPath;
            }

            // Gérer le document d'appel d'offre
            if ($request->hasFile('document_appel_offre')) {
                $documentPath = $request->file('document_appel_offre')->store('job-offers/documents', 'public');
                $validated['document_appel_offre'] = $documentPath;
                $validated['document_appel_offre_nom'] = $request->file('document_appel_offre')->getClientOriginalName();
            }

            // Définir le statut final basé sur l'action
            if ($request->has('action')) {
                if ($request->action === 'publish') {
                    $validated['status'] = 'active';
                } else {
                    $validated['status'] = 'draft';
                }
            }

            $jobOffer = JobOffer::create($validated);

            return redirect()->route('admin.job-offers.index')
                ->with('success', 'Offre d\'emploi créée avec succès.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('STORE - Erreur de validation globale:', [
                'errors' => $e->errors(),
                'message' => $e->getMessage(),
                'request_data' => $request->except(['partner_logo', 'document_appel_offre', '_token'])
            ]);
            throw $e; // Re-lancer pour affichage normal des erreurs de validation
        } catch (\Exception $e) {
            \Log::error('STORE - Erreur générale lors de la création:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['partner_logo', 'document_appel_offre', '_token'])
            ]);
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création de l\'offre. Détails: ' . $e->getMessage()]);
        }
    }

    /**
     * Afficher une offre d'emploi spécifique
     */
    public function show($slug)
    {
        
        $this->authorize('view', $JobOffer);
$jobOffer = JobOffer::findBySlug($slug);
        
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
    public function edit($slug)
    {
        
        $this->authorize('update', $JobOffer);
$jobOffer = JobOffer::findBySlug($slug);
        return view('admin.job-offers.edit', compact('jobOffer'));
    }

    /**
     * Mettre à jour une offre d'emploi
     */
    public function update(Request $request, $slug)
    {
        
        $this->authorize('update', $JobOffer);
$jobOffer = JobOffer::findBySlug($slug);
        
        try {
            // Log des données reçues pour diagnostic
            \Log::info('UPDATE - Données reçues:', [
                'request_data' => $request->except(['partner_logo', 'document_appel_offre']),
                'files' => [
                    'partner_logo' => $request->hasFile('partner_logo') ? 'présent' : 'absent',
                    'document_appel_offre' => $request->hasFile('document_appel_offre') ? 'présent' : 'absent'
                ]
            ]);

            // Validation avec gestion d'erreurs détaillée
            try {
                $validated = $request->validate([
                    'title' => 'required|string|max:255',
                    'description' => 'required|string',
                    'requirements' => 'required|string', // JSON string
                    'benefits' => 'nullable|string',
                    'type' => 'required|in:full-time,part-time,contract,internship',
                    'location' => 'required|string|max:255',
                    'salary_min' => 'nullable|numeric|min:0',
                    'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
                    'application_deadline' => 'required|date',
                    'positions_available' => 'required|integer|min:1',
                    'source' => 'required|in:internal,partner',
                    'partner_name' => 'required_if:source,partner|nullable|string|max:255',
                    'partner_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'contact_email' => 'required|email|max:255',
                    'document_appel_offre' => 'nullable|file|mimes:pdf,doc,docx,odt|max:10240', // 10MB max
                    'is_featured' => 'nullable|boolean',
                    'status' => 'required|in:draft,active,paused,closed',
                ]);
                \Log::info('UPDATE - Validation de base réussie');
            } catch (\Illuminate\Validation\ValidationException $e) {
                \Log::error('UPDATE - Erreur de validation Laravel:', [
                    'errors' => $e->errors(),
                    'message' => $e->getMessage()
                ]);
                throw $e; // Re-lancer l'exception pour affichage normal
            }

            // Validation personnalisée pour les requirements (méthode UPDATE)
            $requirementsJson = $validated['requirements'];
            
            // Debug : log des données reçues
            \Log::info('UPDATE - Requirements reçus:', [
                'data' => $requirementsJson, 
                'type' => gettype($requirementsJson),
                'length' => strlen($requirementsJson),
                'is_empty' => empty($requirementsJson)
            ]);
            
            // Traitement flexible des requirements
            if (empty($requirementsJson) || $requirementsJson === 'null') {
                \Log::warning('UPDATE - Requirements vides détectés');
                return back()
                    ->withInput()
                    ->withErrors(['requirements' => 'Veuillez ajouter au moins une exigence pour le poste.']);
            }
            
            // Si c'est déjà un array (cas rare mais possible)
            if (is_array($requirementsJson)) {
                $requirementsArray = $requirementsJson;
                \Log::info('UPDATE - Requirements déjà en array');
            } else {
                // Décoder le JSON si c'est une chaîne
                \Log::info('UPDATE - Tentative de décodage JSON:', ['json_string' => $requirementsJson]);
                $requirementsArray = json_decode($requirementsJson, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    \Log::error('UPDATE - Erreur JSON requirements:', [
                        'data' => $requirementsJson,
                        'error' => json_last_error_msg(),
                        'error_code' => json_last_error()
                    ]);
                    return back()
                        ->withInput()
                        ->withErrors(['requirements' => 'Format des exigences invalide. Erreur: ' . json_last_error_msg()]);
                }
                \Log::info('UPDATE - JSON décodé avec succès:', ['decoded_array' => $requirementsArray]);
            }
            
            // Vérifier que c'est bien un array
            if (!is_array($requirementsArray)) {
                \Log::error('UPDATE - Requirements n\'est pas un array:', ['type' => gettype($requirementsArray), 'data' => $requirementsArray]);
                return back()
                    ->withInput()
                    ->withErrors(['requirements' => 'Les exigences doivent être une liste valide.']);
            }

            \Log::info('UPDATE - Array requirements avant filtrage:', ['count' => count($requirementsArray), 'items' => $requirementsArray]);

            // Filtrer les exigences vides et vérifier qu'il y en a au moins une
            $filteredRequirements = array_filter($requirementsArray, function($item) {
                return is_string($item) && !empty(trim($item));
            });
            
            \Log::info('UPDATE - Array requirements après filtrage:', ['count' => count($filteredRequirements), 'items' => $filteredRequirements]);
            
            if (empty($filteredRequirements)) {
                \Log::warning('UPDATE - Aucune exigence valide après filtrage');
                return back()
                    ->withInput()
                    ->withErrors(['requirements' => 'Veuillez ajouter au moins une exigence valide pour le poste.']);
            }
            
            // Le modèle JobOffer a un cast 'requirements' => 'array', donc Laravel va automatiquement
            // convertir le tableau en JSON pour la base de données
            $validated['requirements'] = array_values($filteredRequirements);
            \Log::info('UPDATE - Requirements finaux assignés:', ['final_requirements' => $validated['requirements']]);

            // Mapper les valeurs du formulaire vers les valeurs de la base de données
            $typeMapping = [
                'full-time' => 'temps_plein',
                'part-time' => 'temps_partiel',
                'contract' => 'contrat',
                'internship' => 'stage'
            ];

            $sourceMapping = [
                'internal' => 'interne',
                'partner' => 'partenaire'
            ];

            \Log::info('UPDATE - Avant mapping:', [
                'type_original' => $validated['type'],
                'source_original' => $validated['source'],
                'application_deadline_original' => $validated['application_deadline']
            ]);

            // application_deadline est déjà dans le bon format

            // Mapper les valeurs avec vérification
            if (!isset($typeMapping[$validated['type']])) {
                \Log::error('UPDATE - Type invalide:', ['type' => $validated['type']]);
                return back()
                    ->withInput()
                    ->withErrors(['type' => 'Type d\'emploi invalide.']);
            }

            if (!isset($sourceMapping[$validated['source']])) {
                \Log::error('UPDATE - Source invalide:', ['source' => $validated['source']]);
                return back()
                    ->withInput()
                    ->withErrors(['source' => $validated['source']]);
            }

            $validated['type'] = $typeMapping[$validated['type']];
            $validated['source'] = $sourceMapping[$validated['source']];

            \Log::info('UPDATE - Après mapping:', [
                'type_mapped' => $validated['type'],
                'source_mapped' => $validated['source']
            ]);

            // Convertir is_featured en boolean
            $validated['is_featured'] = $request->has('is_featured') ? true : false;
            \Log::info('UPDATE - is_featured défini:', ['is_featured' => $validated['is_featured']]);

            // Traitement du logo partenaire
            \Log::info('UPDATE - Traitement logo partenaire:', [
                'source' => $validated['source'],
                'has_file' => $request->hasFile('partner_logo'),
                'current_logo' => $jobOffer->partner_logo
            ]);

            if ($validated['source'] === 'partenaire' && $request->hasFile('partner_logo')) {
                try {
                    // Supprimer l'ancien logo s'il existe
                    if ($jobOffer->partner_logo) {
                        Storage::disk('public')->delete($jobOffer->partner_logo);
                        \Log::info('UPDATE - Ancien logo supprimé:', ['path' => $jobOffer->partner_logo]);
                    }
                    
                    $file = $request->file('partner_logo');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('assets/partner-logos', $filename, 'public');
                    $validated['partner_logo'] = $path;
                    \Log::info('UPDATE - Nouveau logo sauvegardé:', ['path' => $path]);
                } catch (\Exception $e) {
                    \Log::error('UPDATE - Erreur upload logo:', ['error' => $e->getMessage()]);
                    return back()
                        ->withInput()
                        ->withErrors(['partner_logo' => 'Erreur lors de l\'upload du logo: ' . $e->getMessage()]);
                }
            } elseif ($validated['source'] !== 'partenaire') {
                // Si on change de partenaire vers interne, supprimer le logo
                if ($jobOffer->partner_logo) {
                    Storage::disk('public')->delete($jobOffer->partner_logo);
                    \Log::info('UPDATE - Logo supprimé (changement vers interne)');
                }
                $validated['partner_logo'] = null;
                $validated['partner_name'] = null;
            }

            // Traitement du document d'appel d'offre
            \Log::info('UPDATE - Traitement document:', [
                'has_file' => $request->hasFile('document_appel_offre'),
                'current_document' => $jobOffer->document_appel_offre
            ]);

            if ($request->hasFile('document_appel_offre')) {
                try {
                    // Supprimer l'ancien document s'il existe
                    if ($jobOffer->document_appel_offre) {
                        Storage::disk('public')->delete($jobOffer->document_appel_offre);
                        \Log::info('UPDATE - Ancien document supprimé:', ['path' => $jobOffer->document_appel_offre]);
                    }
                    
                    $documentPath = $request->file('document_appel_offre')->store('job-offers/documents', 'public');
                    $validated['document_appel_offre'] = $documentPath;
                    $validated['document_appel_offre_nom'] = $request->file('document_appel_offre')->getClientOriginalName();
                    \Log::info('UPDATE - Nouveau document sauvegardé:', ['path' => $documentPath]);
                } catch (\Exception $e) {
                    \Log::error('UPDATE - Erreur upload document:', ['error' => $e->getMessage()]);
                    return back()
                        ->withInput()
                        ->withErrors(['document_appel_offre' => 'Erreur lors de l\'upload du document: ' . $e->getMessage()]);
                }
            }

            \Log::info('UPDATE - Données finales avant update:', ['validated_data' => array_keys($validated)]);

            // Tentative de mise à jour
            try {
                $jobOffer->update($validated);
                \Log::info('UPDATE - Mise à jour réussie');
            } catch (\Exception $e) {
                \Log::error('UPDATE - Erreur lors de la mise à jour en base:', [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'sql_error' => $e instanceof \Illuminate\Database\QueryException ? $e->getSql() : 'N/A'
                ]);
                return back()
                    ->withInput()
                    ->withErrors(['error' => 'Erreur lors de la sauvegarde: ' . $e->getMessage()]);
            }

            return redirect()->route('admin.job-offers.show', $jobOffer->slug)
                ->with('success', 'Offre d\'emploi mise à jour avec succès.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('UPDATE - Erreur de validation globale:', [
                'errors' => $e->errors(),
                'message' => $e->getMessage(),
                'request_data' => $request->except(['partner_logo', 'document_appel_offre', '_token'])
            ]);
            throw $e; // Re-lancer pour affichage normal des erreurs de validation
        } catch (\Exception $e) {
            \Log::error('UPDATE - Erreur générale lors de la mise à jour:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['partner_logo', 'document_appel_offre', '_token'])
            ]);
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour de l\'offre. Détails: ' . $e->getMessage()]);
        }
    }

    /**
     * Supprimer une offre d'emploi
     */
    public function destroy($slug)
    {
        
        $this->authorize('delete', $JobOffer);
$jobOffer = JobOffer::findBySlug($slug);
        
        // Supprimer les fichiers associés aux candidatures
        foreach ($jobOffer->applications as $application) {
            if ($application->cv_path) {
                Storage::disk('public')->delete($application->cv_path);
            }
            if ($application->portfolio_path) {
                Storage::disk('public')->delete($application->portfolio_path);
            }
        }

        // Supprimer le logo du partenaire s'il existe
        if ($jobOffer->partner_logo) {
            Storage::disk('public')->delete($jobOffer->partner_logo);
        }

        // Supprimer le document d'appel d'offre s'il existe
        if ($jobOffer->document_appel_offre) {
            Storage::disk('public')->delete($jobOffer->document_appel_offre);
        }

        $jobOffer->delete();

        return redirect()->route('admin.job-offers.index')
            ->with('success', 'Offre d\'emploi supprimée avec succès.');
    }

    /**
     * Dupliquer une offre d'emploi
     */
    public function duplicate($slug)
    {
        $jobOffer = JobOffer::findBySlug($slug);
        
        $newJobOffer = $jobOffer->replicate();
        $newJobOffer->title = $jobOffer->title . ' (Copie)';
        $newJobOffer->slug = null; // Will be auto-generated
        $newJobOffer->status = 'draft';
        $newJobOffer->application_deadline = now()->addMonths(1);
        $newJobOffer->applications_count = 0;
        $newJobOffer->views_count = 0;
        $newJobOffer->save();

        return redirect()->route('admin.job-offers.edit', $newJobOffer->slug)
            ->with('success', 'Offre d\'emploi dupliquée avec succès.');
    }

    /**
     * Changer le statut d'une offre
     */
    public function changeStatus(Request $request, $slug)
    {
        $jobOffer = JobOffer::findBySlug($slug);
        
        $request->validate([
            'status' => 'required|in:draft,active,paused,closed'
        ]);

        $jobOffer->update(['status' => $request->status]);

        return back()->with('success', 'Statut de l\'offre mis à jour avec succès.');
    }

    /**
     * Marquer une offre comme vedette
     */
    public function toggleFeatured($slug)
    {
        $jobOffer = JobOffer::findBySlug($slug);
        
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
