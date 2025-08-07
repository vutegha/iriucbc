<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actualite;
use App\Models\Categorie;
use App\Models\Auteur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Events\ActualiteFeaturedCreated;
use Illuminate\Support\Facades\Storage;

class ActualiteController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Actualite::class);

        $query = Actualite::with(['categorie', 'user']);

        // Appliquer la recherche globale
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('titre', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('resume', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('texte', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('user', function($q) use ($searchTerm) {
                      $q->where('name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('email', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }

        // Appliquer le filtre par statut
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'published':
                    $query->where('is_published', true);
                    break;
                case 'pending':
                    $query->where('is_published', false);
                    break;
                case 'featured':
                    $query->where('a_la_une', true);
                    break;
                case 'urgent':
                    $query->where('en_vedette', true);
                    break;
            }
        }

        // Appliquer le filtre par catégorie s'il est présent
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        $actualites = $query->latest()->paginate(10)->withQueryString();

        // Calculer les statistiques sur TOUTE la base de données (pas seulement la pagination)
        $stats = [
            'total' => Actualite::count(),
            'published' => Actualite::where('is_published', true)->count(),
            'pending' => Actualite::where('is_published', false)->count(),
            'this_week' => Actualite::where('created_at', '>=', now()->startOfWeek())->count(),
            'featured' => Actualite::where('a_la_une', true)->count(),
        ];

        // Charger toutes les catégories pour le select du filtre
        $categories = Categorie::orderBy('nom')->get();

        return view('admin.actualite.index', compact('actualites', 'categories', 'stats'));
    }

    public function create()
    {
        $this->authorize('create', Actualite::class);

        return view('admin.actualite.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Actualite::class);

        try {
            $validated = $request->validate([
                'titre' => 'required|string|min:5|max:255',
                'resume' => 'nullable|string|min:10', // Supprimer max, ajouter min optionnel
                'texte' => 'required|string|min:20',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            ], [
                'titre.required' => 'Le titre est obligatoire.',
                'titre.min' => 'Le titre doit contenir au moins 5 caractères.',
                'titre.max' => 'Le titre ne peut pas dépasser 255 caractères.',
                'resume.min' => 'Si vous remplissez le résumé, il doit contenir au moins 10 caractères.',
                'texte.required' => 'Le contenu de l\'actualité est obligatoire.',
                'texte.min' => 'Le contenu doit être plus détaillé (au moins 20 caractères).',
                'image.image' => 'Le fichier doit être une image.',
                'image.mimes' => 'L\'image doit être au format JPG, JPEG, PNG ou WebP.',
                'image.max' => 'L\'image ne peut pas dépasser 5 MB.',
            ]);

            // Gestion des checkboxes (Boolean)  
            $validated['a_la_une'] = $request->boolean('a_la_une');
            $validated['en_vedette'] = $request->boolean('en_vedette');

            // Traitement de l'image
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                
                // Validation supplémentaire
                if (!$image->isValid()) {
                    throw new \Exception('Le fichier image est corrompu ou invalide.');
                }
                
                $filename = uniqid('img_') . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('assets/images', $filename, 'public');
                $validated['image'] = $path;
            }

            // Associer l'utilisateur connecté
            $validated['user_id'] = auth()->id();

            $actualite = Actualite::create($validated);

            // NOTE: L'événement ActualiteFeaturedCreated sera déclenché uniquement 
            // lors de l'action de modération "publier" pour respecter le workflow

            return redirect()->route('admin.actualite.index')
                ->with('success', 'Actualité créée avec succès.')
                ->with('alert', '<span class="alert alert-success"><strong>Succès !</strong> Votre actualité a été enregistrée et sera visible après modération.</span>');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Veuillez corriger les erreurs dans le formulaire.')
                ->with('alert', '<span class="alert alert-warning"><strong>Attention !</strong> Veuillez corriger les erreurs signalées dans le formulaire.</span>');
                
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création d\'actualité', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['image']) // Exclure l'image des logs
            ]);

            return back()
                ->withInput()
                ->with('error', 'Une erreur technique est survenue. Veuillez réessayer.')
                ->with('alert', '<span class="alert alert-danger"><strong>Erreur !</strong> ' . e($e->getMessage()) . '</span>');
        }
    }

    public function show(Actualite $actualite)
    {
        $this->authorize('view', $actualite);

        // S'assurer que l'actualité a un slug seulement si elle a un titre
        if (empty($actualite->slug) && !empty($actualite->titre)) {
            $actualite->slug = now()->format('Ymd') . '-' . \Illuminate\Support\Str::slug($actualite->titre);
            $actualite->save();
        }
        
        return view('admin.actualite.show', compact('actualite'));
    }

    public function edit(Actualite $actualite)
    {
        $this->authorize('update', $actualite);
        
        return view('admin.actualite.edit', compact('actualite'));
    }

    public function update(Request $request, Actualite $actualite)
    {
        $this->authorize('update', $actualite);
        
        try {
            $validated = $request->validate([
                'titre' => 'required|string|min:5|max:255',
                'resume' => 'nullable|string|min:10', // Supprimer max, ajouter min optionnel
                'texte' => 'required|string|min:20',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            ], [
                'titre.required' => 'Le titre est obligatoire.',
                'titre.min' => 'Le titre doit contenir au moins 5 caractères.',
                'titre.max' => 'Le titre ne peut pas dépasser 255 caractères.',
                'resume.min' => 'Si vous remplissez le résumé, il doit contenir au moins 10 caractères.',
                'texte.required' => 'Le contenu de l\'actualité est obligatoire.',
                'texte.min' => 'Le contenu doit être plus détaillé (au moins 20 caractères).',
                'image.image' => 'Le fichier doit être une image.',
                'image.mimes' => 'L\'image doit être au format JPG, JPEG, PNG ou WebP.',
                'image.max' => 'L\'image ne peut pas dépasser 5 MB.',
            ]);
            
            // Préparer les données pour la mise à jour
            $updateData = $validated;
            
            // Gestion des checkboxes avec boolean helper
            $updateData['a_la_une'] = $request->boolean('a_la_une');
            $updateData['en_vedette'] = $request->boolean('en_vedette');

            // Traitement de l'image
            if ($request->hasFile('image')) {
                // Validation supplémentaire
                $image = $request->file('image');
                if (!$image->isValid()) {
                    throw new \Exception('Le fichier image est corrompu ou invalide.');
                }

                // Supprimer l'ancienne image
                if ($actualite->image && Storage::disk('public')->exists($actualite->image)) {
                    Storage::disk('public')->delete($actualite->image);
                }

                $filename = uniqid('img_') . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('assets/images', $filename, 'public');
                $updateData['image'] = $path;
            }

            $actualite->update($updateData);

            return redirect()->route('admin.actualite.index')
                ->with('success', 'Actualité mise à jour avec succès.')
                ->with('alert', '<span class="alert alert-success"><strong>Succès !</strong> Les modifications ont été enregistrées.</span>');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Veuillez corriger les erreurs dans le formulaire.')
                ->with('alert', '<span class="alert alert-warning"><strong>Attention !</strong> Veuillez corriger les erreurs signalées dans le formulaire.</span>');
                
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour d\'actualité', [
                'actualite_id' => $actualite->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['image'])
            ]);

            return back()
                ->withInput()
                ->with('error', 'Une erreur technique est survenue lors de la mise à jour.')
                ->with('alert', '<span class="alert alert-danger"><strong>Erreur !</strong> ' . e($e->getMessage()) . '</span>');
        }
    }

    public function destroy(Actualite $actualite)
    {
        $this->authorize('delete', $actualite);

        try {
            // Supprimer l'image
            if ($actualite->image && Storage::disk('public')->exists($actualite->image)) {
                Storage::disk('public')->delete($actualite->image);
            }

            $actualite->delete();

            // Réponse en JSON pour les appels AJAX
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Actualité supprimée avec succès.'
                ]);
            }

            return redirect()->route('admin.actualite.index')
                ->with('alert', '<span class="alert alert-success">Actualité supprimée avec succès.</span>');
        } catch (\Exception $e) {
            // Réponse en JSON pour les appels AJAX
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur : ' . $e->getMessage()
                ], 500);
            }

            return back()
                ->with('alert', '<span class="alert alert-danger">Erreur : ' . e($e->getMessage()) . '</span>');
        }
    }

    public function publish(Request $request, Actualite $actualite)
    {
        $this->authorize('moderate', $actualite);

        try {
            $actualite->update([
                'is_published' => true,
                'published_at' => now(),
                'published_by' => auth()->id(),
                'moderation_comment' => $request->input('comment')
            ]);
            
            // Déclencher l'événement newsletter uniquement lors de la publication officielle
            // si l'actualité est en vedette ET à la une
            if ($actualite->en_vedette && $actualite->a_la_une) {
                try {
                    ActualiteFeaturedCreated::dispatch($actualite);
                    \Log::info('Événement ActualiteFeaturedCreated déclenché lors de la publication', [
                        'actualite_id' => $actualite->id,
                        'titre' => $actualite->titre
                    ]);
                } catch (\Exception $e) {
                    \Log::warning('Erreur lors du déclenchement de l\'événement ActualiteFeaturedCreated', [
                        'actualite_id' => $actualite->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Actualité publiée avec succès'
                ]);
            }
            
            return redirect()->route('admin.actualite.show', $actualite->slug)
                ->with('success', 'Actualité publiée avec succès');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la publication : ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.actualite.show', $actualite->slug)
                ->with('error', 'Erreur lors de la publication : ' . $e->getMessage());
        }
    }

    public function unpublish(Request $request, Actualite $actualite)
    {
        $this->authorize('moderate', $actualite);

        try {
            $actualite->update([
                'is_published' => false,
                'published_at' => null,
                'published_by' => null,
                'moderation_comment' => $request->input('comment')
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Actualité dépubliée avec succès'
                ]);
            }
            
            return redirect()->route('admin.actualite.show', $actualite->slug)
                ->with('success', 'Actualité dépubliée avec succès');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la dépublication : ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.actualite.show', $actualite->slug)
                ->with('error', 'Erreur lors de la dépublication : ' . $e->getMessage());
        }
    }

    public function moderate(Request $request, Actualite $actualite)
    {
        $this->authorize('moderate', $actualite);

        try {
            $validated = $request->validate([
                'action' => 'required|in:approve,reject',
                'moderation_comment' => 'nullable|string|max:1000',
            ]);

            $actualite->moderation_comment = $validated['moderation_comment'] ?? null;

            if ($validated['action'] === 'approve') {
                if ($actualite->moderation_status !== 'published') {
                    $actualite->publish(auth()->user(), $validated['moderation_comment']);
                }
            } else {
                if ($actualite->moderation_status === 'published') {
                    $actualite->unpublish($validated['moderation_comment']);
                } else {
                    $actualite->moderation_status = 'rejected';
                    $actualite->save();
                }
            }

            return redirect()->route('admin.actualite.show', $actualite->slug)
                ->with('success', 'Modération mise à jour avec succès');
        } catch (\Exception $e) {
            return redirect()->route('admin.actualite.show', $actualite->slug)
                ->with('error', 'Erreur lors de la modération : ' . $e->getMessage());
        }
    }
}
