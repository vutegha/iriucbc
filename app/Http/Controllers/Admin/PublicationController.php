<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Publication;
use App\Models\Auteur;
use App\Models\Categorie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
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
        $query = Publication::with('auteur', 'categorie');

        if ($request->filled('auteur')) {
            $query->where('auteur_id', $request->auteur);
        }

        if ($request->filled('categorie')) {
            $query->where('categorie_id', $request->categorie);
        }

        $publications = $query->latest()->paginate(10)->appends($request->query());

        $auteurs = Auteur::all();
        $categories = Categorie::all();

        return view('admin.publication.index', compact('publications', 'auteurs', 'categories', 'request'));
    }

    public function create()
    {
        $auteurs = Auteur::all();
        $categories = Categorie::all();
        return view('admin.publication.create', compact('auteurs', 'categories'));
    }

    public function store(Request $request)
    {
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
                $filename = $titre_clean . '_' . time() . '.' . $file->getClientOriginalExtension();
                
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

            Log::info('Publication créée avec succès', ['id' => $publication->id, 'fichier' => $validated['fichier_pdf']]);

            return redirect()->route('admin.publication.index')
                ->with('alert', '<span class="alert alert-success">Publication enregistrée avec succès. Fichier: ' . basename($validated['fichier_pdf']) . '</span>');
                
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
                ->with('alert', '<span class="alert alert-danger">Une erreur est survenue : ' . $e->getMessage() . '</span>')
                ->withInput();
        }
    }

    /**
     * Affiche une publication spécifique
     */
    public function show(Publication $publication)
    {
        // Vérifier les permissions
        $this->authorize('view', $publication);
        
        // Charger les relations nécessaires
        $publication->load(['auteurs', 'categorie']);
        
        return view('admin.publication.show', compact('publication'));
    }

    public function edit(Publication $publication)
    {
        // Vérifier les permissions
        $this->authorize('update', $publication);
        
        $auteurs = Auteur::all();
        $categories = Categorie::all();

        return view('admin.publication.edit', compact('publication', 'auteurs', 'categories'));
    }

    public function update(Request $request, Publication $publication)
    {
        // Vérifier les permissions
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
                $filename = $titre_clean . '_' . time() . '.' . $file->getClientOriginalExtension();
                
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

            // Mise à jour de la publication
            $publication->update($validated);

            // Synchroniser les auteurs avec l'ordre
            $auteursWithOrder = [];
            foreach ($auteurs as $index => $auteurId) {
                $auteursWithOrder[$auteurId] = ['ordre' => $index + 1];
            }
            $publication->auteurs()->sync($auteursWithOrder);

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
                ->with('alert', '<span class="alert alert-danger">Une erreur est survenue : ' . $e->getMessage() . '</span>')
                ->withInput();
        }
    }

    public function updateFeatures(Request $request, Publication $publication)
    {
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
        // Vérifier les permissions
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
                ->with('alert', '<span class="alert alert-danger">Erreur lors de la suppression : ' . $e->getMessage() . '</span>');
        }
    }

    /**
     * Publier une publication
     */
    public function publish(Request $request, Publication $publication)
    {
        // Vérifier les permissions
        $this->authorize('publish', $publication);

        try {
            $publication->update([
                'is_published' => true,
                'published_at' => now(),
                'published_by' => auth()->id(),
                'moderation_comment' => $request->input('comment')
            ]);

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
                ->with('alert', '<span class="alert alert-danger">Erreur lors de la publication : ' . $e->getMessage() . '</span>');
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
                ->with('alert', '<span class="alert alert-danger">Erreur lors de la dépublication : ' . $e->getMessage() . '</span>');
        }
    }
}
