<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Publication;
use App\Models\Auteur;
use App\Models\Categorie;
use App\Events\PublicationFeaturedCreated;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PublicationController extends Controller
{
    /**
     * Nettoie un nom de fichier en supprimant les caractères dangereux
     */
    private function sanitizeFilename($filename)
    {
        // Supprimer les caractères dangereux pour Windows et Linux
        $dangerous = ['/', '\\', ':', '*', '?', '"', '<', '>', '|', "'", '&', '%', '#', '@', '!', '^', '(', ')', '[', ']', '{', '}', '~', '`', ';', ',', '=', '+', '€', '$'];
        $filename = str_replace($dangerous, '_', $filename);
        
        // Transformer les accents en équivalents ASCII
        $accents = [
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae',
            'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
            'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o',
            'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ý' => 'y', 'ÿ' => 'y',
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE',
            'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E',
            'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O',
            'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y'
        ];
        
        foreach ($accents as $accent => $ascii) {
            $filename = str_replace($accent, $ascii, $filename);
        }
        
        // Supprimer tous les caractères non-alphanumériques sauf underscores et tirets
        $filename = preg_replace('/[^a-zA-Z0-9_\-.]/', '_', $filename);
        
        // Supprimer les espaces multiples et les remplacer par des underscores
        $filename = preg_replace('/\s+/', '_', $filename);
        
        // Supprimer les underscores multiples
        $filename = preg_replace('/_+/', '_', $filename);
        
        // Supprimer les underscores en début et fin
        $filename = trim($filename, '_');
        
        // Limiter la longueur pour éviter les problèmes de chemins trop longs
        if (strlen($filename) > 50) {
            $filename = substr($filename, 0, 50);
            $filename = rtrim($filename, '_');
        }
        
        // Si le nom est vide après nettoyage, utiliser un nom par défaut
        if (empty($filename)) {
            $filename = 'publication';
        }
        
        return $filename;
    }
    public function index(Request $request)
    {
        $this->authorize('viewAny', Publication::class);
        
        $query = Publication::with('auteurs', 'categorie');

        // Recherche globale
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('titre', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('resume', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('citation', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('auteurs', function($q) use ($searchTerm) {
                      $q->where('nom', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('prenom', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }

        if ($request->filled('auteur')) {
            $query->where('auteur_id', $request->auteur);
        }

        if ($request->filled('categorie')) {
            $query->where('categorie_id', $request->categorie);
        }

        // Filtre par statut
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'published':
                    $query->where('is_published', true);
                    break;
                case 'pending':
                    $query->where('is_published', false);
                    break;
                case 'draft':
                    $query->draft();
                    break;
            }
        }

        $publications = $query->latest()->paginate(10)->appends($request->query());

        // Statistiques globales (sur toute la base, pas juste la pagination)
        $stats = [
            'total' => Publication::count(),
            'published' => Publication::where('is_published', true)->count(),
            'pending' => Publication::where('is_published', false)->count(),
            'draft' => Publication::draft()->count(),
            'featured' => Publication::where('en_vedette', true)->count(),
            'this_month' => Publication::where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        $auteurs = Auteur::all();
        $categories = Categorie::all();

        return view('admin.publication.index', compact('publications', 'auteurs', 'categories', 'request', 'stats'));
    }

    public function create()
    {
        $this->authorize('create', Publication::class);
        
        $auteurs = Auteur::all();
        $categories = Categorie::all();
        return view('admin.publication.create', compact('auteurs', 'categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Publication::class);
        try {
            // Log de debug
            Log::info('Début création publication', [
                'titre' => $request->get('titre'),
                'has_file' => $request->hasFile('fichier_pdf'),
                'file_info' => $request->hasFile('fichier_pdf') ? [
                    'name' => $request->file('fichier_pdf')->getClientOriginalName(),
                    'size' => $request->file('fichier_pdf')->getSize(),
                    'mime' => $request->file('fichier_pdf')->getClientMimeType(),
                ] : null
            ]);

            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'resume' => 'required|string',
                'citation' => 'nullable|string',
                'a_la_une' => 'nullable|boolean',
                'en_vedette' => 'nullable|boolean',
                'auteurs' => 'required|array',
                'auteurs.*' => 'exists:auteurs,id',
                'categorie_id' => 'required|exists:categories,id',
                'fichier_pdf' => 'required|file|mimes:pdf|max:51200',
            ], [
                'fichier_pdf.required' => 'Le fichier PDF est obligatoire pour toute publication.',
                'fichier_pdf.file' => 'Le fichier téléchargé doit être un fichier valide.',
                'fichier_pdf.mimes' => 'Le fichier doit être au format PDF uniquement.',
                'fichier_pdf.max' => 'Le fichier ne doit pas dépasser 50 MB.',
                'titre.required' => 'Le titre est obligatoire.',
                'resume.required' => 'Le résumé est obligatoire.',
                'auteurs.required' => 'Au moins un auteur doit être sélectionné.',
                'categorie_id.required' => 'Une catégorie doit être sélectionnée.',
            ]);

            // Ensure boolean fields are set to false if not present
            $validated['a_la_une'] = $request->has('a_la_une') ? 1 : 0;
            $validated['en_vedette'] = $request->has('en_vedette') ? 1 : 0;

            // Traitement du fichier
            if ($request->hasFile('fichier_pdf')) {
                $file = $request->file('fichier_pdf');
                
                // Générer nom de fichier sécurisé
                $titre_clean = $this->sanitizeFilename($validated['titre']);
                $filename = $titre_clean . '_' . time() . '.' . $file->extension();
                
                try {
                    // Méthode simple et fiable
                    $path = $file->storeAs('assets', $filename, 'public');
                    
                    if ($path) {
                        $validated['fichier_pdf'] = $path;
                        Log::info('Fichier stocké avec succès', ['path' => $path]);
                    } else {
                        throw new \Exception('La méthode storeAs() a retourné false');
                    }
                    
                } catch (\Exception $e) {
                    Log::error('Erreur stockage fichier', [
                        'error' => $e->getMessage(),
                        'file' => $filename
                    ]);
                    throw new \Exception('Impossible de sauvegarder le fichier: ' . $e->getMessage());
                }
            } else {
                throw new \Exception('Aucun fichier reçu');
            }

            // Extraire les auteurs avant de créer la publication
            $auteurs = $validated['auteurs'];
            unset($validated['auteurs']);

            // Créer la publication
            $publication = Publication::create($validated);
            
            // Attacher les auteurs avec l'ordre
            $auteursWithOrder = [];
            foreach ($auteurs as $index => $auteurId) {
                $auteursWithOrder[$auteurId] = ['ordre' => $index + 1];
            }
            $publication->auteurs()->attach($auteursWithOrder);

            // NOTE: L'événement PublicationFeaturedCreated sera déclenché uniquement 
            // lors de l'action de modération "publier" pour respecter le workflow

            // Note: Les miniatures PDF sont maintenant générées côté client avec PDF.js
            // Pas besoin de job en arrière-plan

            Log::info('Publication créée avec succès', ['id' => $publication->id, 'fichier' => $validated['fichier_pdf']]);

            return redirect()->route('admin.publication.index')
                ->with('alert', '<span class="alert alert-success">Publication enregistrée avec succès. Fichier: ' . e(basename($validated['fichier_pdf'])) . '</span>');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Erreur de validation', ['errors' => $e->errors()]);
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('alert', '<span class="alert alert-danger">Erreur de validation. Veuillez vérifier les champs requis.</span>');
        } catch (\Exception $e) {
            Log::error('Erreur création publication', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->back()
                ->with('alert', '<span class="alert alert-danger">Une erreur est survenue : ' . e($e->getMessage()) . '</span>')
                ->withInput();
        }
    }

    /**
     * Affiche une publication spécifique
     */
    public function show(Publication $publication)
    {
        
        $this->authorize('view', $publication);
        
        // Charger les relations nécessaires
        $publication->load(['auteurs', 'categorie']);
        
        return view('admin.publication.show', compact('publication'));
    }

    public function edit(Publication $publication)
    {
        
        $this->authorize('update', $publication);
        
        $auteurs = Auteur::all();
        $categories = Categorie::all();

        return view('admin.publication.edit', compact('publication', 'auteurs', 'categories'));
    }

    public function update(Request $request, Publication $publication)
    {
        
        $this->authorize('update', $publication);
        
        try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'resume' => 'required|string',
                'citation' => 'nullable|string',
                'a_la_une' => 'nullable|boolean',
                'en_vedette' => 'nullable|boolean',
                'auteurs' => 'required|array',
                'auteurs.*' => 'exists:auteurs,id',
                'categorie_id' => 'required|exists:categories,id',
                'fichier_pdf' => 'nullable|file|mimes:pdf|max:51200',
            ], [
                'fichier_pdf.file' => 'Le fichier téléchargé doit être un fichier valide.',
                'fichier_pdf.mimes' => 'Le fichier doit être au format PDF uniquement.',
                'fichier_pdf.max' => 'Le fichier ne doit pas dépasser 50 MB.',
                'titre.required' => 'Le titre est obligatoire.',
                'resume.required' => 'Le résumé est obligatoire.',
                'auteurs.required' => 'Au moins un auteur doit être sélectionné.',
                'categorie_id.required' => 'Une catégorie doit être sélectionnée.',
            ]);

            // Forcer les booléens
            $validated['a_la_une'] = $request->has('a_la_une') ? 1 : 0;
            $validated['en_vedette'] = $request->has('en_vedette') ? 1 : 0;

            // Gérer le fichier PDF/Word/PowerPoint
            if ($request->hasFile('fichier_pdf')) {
                $file = $request->file('fichier_pdf');
                
                // Supprimer l'ancien fichier s'il existe
                if ($publication->fichier_pdf && Storage::disk('public')->exists($publication->fichier_pdf)) {
                    Storage::disk('public')->delete($publication->fichier_pdf);
                }

                // Générer nom de fichier sécurisé
                $titre_clean = $this->sanitizeFilename($validated['titre']);
                $filename = $titre_clean . '_' . time() . '.' . $file->extension();
                
                try {
                    $path = $file->storeAs('assets', $filename, 'public');
                    
                    if ($path) {
                        $validated['fichier_pdf'] = $path;
                        Log::info('Fichier mis à jour avec succès', ['path' => $path]);
                    } else {
                        throw new \Exception('La méthode storeAs() a retourné false');
                    }
                    
                } catch (\Exception $e) {
                    Log::error('Erreur mise à jour fichier', [
                        'error' => $e->getMessage(),
                        'file' => $filename
                    ]);
                    throw new \Exception('Impossible de sauvegarder le fichier: ' . $e->getMessage());
                }
            }

            // Extraire les auteurs avant de mettre à jour la publication
            $auteurs = $validated['auteurs'];
            unset($validated['auteurs']);

            // Vérifier si la publication vient d'être mise en avant
            $wasFeatured = !$publication->is_featured && $validated['is_featured'] ?? false;
            $wasPublished = !$publication->is_published && $validated['is_published'] ?? false;
            
            // Mise à jour de la publication
            $publication->update($validated);

            // Synchroniser les auteurs avec l'ordre
            $auteursWithOrder = [];
            foreach ($auteurs as $index => $auteurId) {
                $auteursWithOrder[$auteurId] = ['ordre' => $index + 1];
            }
            $publication->auteurs()->sync($auteursWithOrder);

            // NOTE: L'événement PublicationFeaturedCreated sera déclenché uniquement 
            // lors de l'action de modération "publier" pour respecter le workflow

            // Note: Les miniatures PDF sont maintenant générées côté client avec PDF.js
            // Pas besoin de job en arrière-plan pour les nouveaux fichiers

            $message = 'Publication mise à jour avec succès.';
            if (isset($path)) {
                $message .= ' Nouveau fichier: ' . basename($path);
            }

            return redirect()->route('admin.publication.index')
                ->with('alert', '<span class="alert alert-success">' . $message . '</span>');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('alert', '<span class="alert alert-danger">Erreur de validation. Veuillez vérifier les champs.</span>');
        } catch (\Exception $e) {
            Log::error('Erreur mise à jour publication', [
                'message' => $e->getMessage(),
                'publication_id' => $publication->id
            ]);
            
            return redirect()->back()
                ->with('alert', '<span class="alert alert-danger">Une erreur est survenue : ' . e($e->getMessage()) . '</span>')
                ->withInput();
        }
    }

    public function updateFeatures(Request $request, Publication $publication)
    {
        $this->authorize('update', $publication);
        $validated = $request->validate([
            'en_vedette' => 'required|boolean',
            'a_la_une' => 'required|boolean',
        ]);

        $publication->update([
            'en_vedette' => $validated['en_vedette'],
            'a_la_une' => $validated['a_la_une'],
        ]);

        return redirect()->route('admin.publication.index')
            ->with('alert', '<span class="alert alert-success">Champs mis à jour avec succès.</span>');
    }

    public function destroy(Publication $publication)
    {
        
        $this->authorize('delete', $publication);
        
        try {
            // Supprimer le fichier associé s'il existe
            if ($publication->fichier_pdf && Storage::disk('public')->exists($publication->fichier_pdf)) {
                Storage::disk('public')->delete($publication->fichier_pdf);
            }

            // Détacher les auteurs
            $publication->auteurs()->detach();
            
            // Supprimer la publication
            $publication->delete();

            return redirect()->route('admin.publication.index')
                ->with('alert', '<span class="alert alert-success">Publication supprimée avec succès.</span>');
        } catch (\Exception $e) {
            return redirect()->route('admin.publication.index')
                ->with('alert', '<span class="alert alert-danger">Erreur lors de la suppression : ' . e($e->getMessage()) . '</span>');
        }
    }

    /**
     * Publier une publication
     */
    public function publish(Request $request, Publication $publication)
    {
        // DEBUG: Log de début d'action
        Log::info('🚀 DÉBUT ACTION PUBLISH', [
            'publication_id' => $publication->id,
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email ?? 'unknown',
            'publication_titre' => $publication->titre,
            'is_published_avant' => $publication->is_published,
            'request_comment' => $request->input('comment'),
            'request_method' => $request->method(),
            'request_url' => $request->fullUrl()
        ]);

        // Vérifier les permissions
        $this->authorize('publish', $publication);

        try {
            $publication->update([
                'is_published' => true,
                'published_at' => now(),
                'published_by' => auth()->id(),
                'moderation_comment' => $request->input('comment')
            ]);

            Log::info('✅ PUBLICATION MISE À JOUR', [
                'publication_id' => $publication->id,
                'is_published_apres' => $publication->fresh()->is_published,
                'published_at' => $publication->fresh()->published_at ? $publication->fresh()->published_at->format('Y-m-d H:i:s') : null,
                'published_by' => $publication->fresh()->published_by
            ]);

            // Déclencher l'événement newsletter pour toutes les publications publiées
            try {
                Log::info('🎯 AVANT DISPATCH ÉVÉNEMENT', [
                    'publication_id' => $publication->id,
                    'jobs_avant' => DB::table('jobs')->count()
                ]);
                
                PublicationFeaturedCreated::dispatch($publication);
                
                sleep(1); // Attendre un peu pour que l'événement soit traité
                
                Log::info('✅ ÉVÉNEMENT DISPATCHÉ', [
                    'publication_id' => $publication->id,
                    'titre' => $publication->titre,
                    'en_vedette' => $publication->en_vedette,
                    'a_la_une' => $publication->a_la_une,
                    'jobs_apres' => DB::table('jobs')->count()
                ]);
            } catch (\Exception $e) {
                Log::error('❌ ERREUR DISPATCH ÉVÉNEMENT', [
                    'publication_id' => $publication->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Publication publiée avec succès.'
                ]);
            }

            return redirect()->back()
                ->with('alert', '<span class="alert alert-success">Publication publiée avec succès.</span>');
                
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la publication : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('alert', '<span class="alert alert-danger">Erreur lors de la publication : ' . e($e->getMessage()) . '</span>');
        }
    }

    /**
     * Dépublier une publication
     */
    public function unpublish(Request $request, Publication $publication)
    {
        // Vérifier les permissions
        $this->authorize('unpublish', $publication);

        try {
            $publication->update([
                'is_published' => false,
                'moderation_comment' => $request->input('comment')
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Publication dépubliée avec succès.'
                ]);
            }

            return redirect()->back()
                ->with('alert', '<span class="alert alert-success">Publication dépubliée avec succès.</span>');
                
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la dépublication : ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('alert', '<span class="alert alert-danger">Erreur lors de la dépublication : ' . e($e->getMessage()) . '</span>');
        }
    }

    /**
     * Publications en attente de modération.
     */
    public function pendingModeration()
    {
        $this->authorize('viewAny', Publication::class);

        $publications = Publication::pendingModeration()
                                   ->with('categorie')
                                   ->latest()
                                   ->paginate(10);

        return view('admin.publication.pending', compact('publications'));
    }

    /**
     * Basculer l'affichage « à la une ».
     */
    public function toggleUne(Publication $publication)
    {
        $this->authorize('update', $publication);

        $publication->update(['a_la_une' => !$publication->a_la_une]);

        return back()->with('success', $publication->a_la_une
            ? 'Publication mise à la une.'
            : 'Publication retirée de la une.');
    }
}
