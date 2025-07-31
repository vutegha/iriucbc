<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actualite;
use App\Models\Categorie;
use App\Models\Auteur;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Events\ActualiteFeaturedCreated;
use Illuminate\Support\Facades\Storage;

class ActualiteController extends Controller
{
    public function index(Request $request)
    {
        // $this->authorize('viewAny', Actualite::class);

        $query = Actualite::with(['categorie']);

        // Appliquer le filtre par catégorie s'il est présent
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        $actualites = $query->latest()->paginate(10);

        // Charger toutes les catégories pour le select du filtre
        $categories = Categorie::orderBy('nom')->get();

        return view('admin.actualite.index', compact('actualites', 'categories'));
    }

    public function create()
    {
        // $this->authorize('create', Actualite::class);

        return view('admin.actualite.create');
    }

    public function store(Request $request)
    {
        // $this->authorize('create', Actualite::class);

        try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'resume' => 'nullable|string|max:255',
                'texte' => 'required|string',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            ]);

            // Gestion des checkboxes (Boolean)  
            $validated['a_la_une'] = $request->boolean('a_la_une');
            $validated['en_vedette'] = $request->boolean('en_vedette');

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = uniqid('img_') . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('assets/images', $filename, 'public');
                $validated['image'] = $path;
            }

            $actualite = Actualite::create($validated);

            // Déclencher l'événement si l'actualité est en vedette ET à la une
            if ($actualite->en_vedette && $actualite->a_la_une) {
                ActualiteFeaturedCreated::dispatch($actualite);
            }

            return redirect()->route('admin.actualite.index')
                ->with('alert', '<span class="alert alert-success">Actualité enregistrée avec succès.</span>');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('alert', '<span class="alert alert-danger">Erreur : ' . e($e->getMessage()) . '</span>');
        }
    }

    public function show(Actualite $actualite)
    {
        // $this->authorize('view', $actualite);

        // S'assurer que l'actualité a un slug seulement si elle a un titre
        if (empty($actualite->slug) && !empty($actualite->titre)) {
            $actualite->slug = now()->format('Ymd') . '-' . \Illuminate\Support\Str::slug($actualite->titre);
            $actualite->save();
        }
        
        return view('admin.actualite.show', compact('actualite'));
    }

    public function edit(Actualite $actualite)
    {
        // $this->authorize('update', $actualite);
        
        return view('admin.actualite.edit', compact('actualite'));
    }

    public function update(Request $request, Actualite $actualite)
    {
        // $this->authorize('update', $actualite);
        
        try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'resume' => 'nullable|string|max:255',
                'texte' => 'required|string',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            ]);
            
            // Préparer les données pour la mise à jour
            $updateData = $validated;
            
            // Debug: Log request data
            \Log::info('Request data:', $request->all());
            \Log::info('Has a_la_une: ' . ($request->has('a_la_une') ? 'true' : 'false'));
            \Log::info('Has en_vedette: ' . ($request->has('en_vedette') ? 'true' : 'false'));
            \Log::info('a_la_une value: ' . $request->input('a_la_une'));
            \Log::info('en_vedette value: ' . $request->input('en_vedette'));
            
            // Gestion des checkboxes avec boolean helper
            $updateData['a_la_une'] = $request->boolean('a_la_une');
            $updateData['en_vedette'] = $request->boolean('en_vedette');
            
            \Log::info('Final validated data:', ['data' => $validated]);
            \Log::info('Update data with checkboxes:', ['data' => $updateData]);

            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image
                if ($actualite->image && Storage::disk('public')->exists($actualite->image)) {
                    Storage::disk('public')->delete($actualite->image);
                }

                $filename = uniqid('img_') . '.' . $request->file('image')->getClientOriginalExtension();
                $path = $request->file('image')->storeAs('assets/images', $filename, 'public');
                $updateData['image'] = $path;
            }

            $actualite->update($updateData);
            
            \Log::info('Actualite after update:', ['actualite' => $actualite->fresh()->toArray()]);

            return redirect()->route('admin.actualite.index')
                ->with('alert', '<span class="alert alert-success">Actualité mise à jour avec succès.</span>');
        } catch (\Exception $e) {
            \Log::error('Error updating actualite:', ['error' => $e->getMessage()]);
            return back()->withInput()
                ->with('alert', '<span class="alert alert-danger">Erreur : ' . e($e->getMessage()) . '</span>');
        }
    }

    public function destroy(Actualite $actualite)
    {
        // $this->authorize('delete', $actualite);

        try {
            // Supprimer l'image
            if ($actualite->image && Storage::disk('public')->exists($actualite->image)) {
                Storage::disk('public')->delete($actualite->image);
            }

            $actualite->delete();

            return redirect()->route('admin.actualite.index')
                ->with('alert', '<span class="alert alert-success">Actualité supprimée avec succès.</span>');
        } catch (\Exception $e) {
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
        // $this->authorize('moderate', $actualite);

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
