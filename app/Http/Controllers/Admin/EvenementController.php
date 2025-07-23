<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class EvenementController extends Controller
{
    public function index(Request $request)
    {
        $query = Evenement::query();

        // Filtres
        if ($request->filled('etat')) {
            switch ($request->etat) {
                case 'a_venir':
                    $query->aVenir();
                    break;
                case 'en_cours':
                    $query->enCours();
                    break;
                case 'passe':
                    $query->passe();
                    break;
            }
        }

        if ($request->filled('annee')) {
            $query->whereYear('date_debut', $request->annee);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('titre', 'like', '%' . $request->search . '%')
                  ->orWhere('lieu', 'like', '%' . $request->search . '%');
            });
        }

        $evenements = $query->latest('date_debut')->paginate(10);

        // Pour les filtres
        $anneesDisponibles = Evenement::selectRaw('YEAR(date_debut) as annee')
                                    ->whereNotNull('date_debut')
                                    ->distinct()
                                    ->orderBy('annee', 'desc')
                                    ->pluck('annee')
                                    ->toArray();

        return view('admin.evenements.index', compact('evenements', 'anneesDisponibles'));
    }

    public function create()
    {
        return view('admin.evenements.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'resume' => 'nullable|string|max:500',
                'description' => 'required|string',
                'date_debut' => 'required|date|after_or_equal:today',
                'date_fin' => 'nullable|date|after_or_equal:date_debut',
                'lieu' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
                'rapport_url' => 'nullable|url|max:255',
            ]);

            if ($request->hasFile('image')) {
                $filename = 'evenements/' . uniqid() . '_' . preg_replace('/\s+/', '_', $request->file('image')->getClientOriginalName());
                $path = $request->file('image')->storeAs('assets/images', $filename, 'public');
                $validated['image'] = $path;
            }

            Evenement::create($validated);

            return redirect()->route('admin.evenements.index')
                ->with('alert', '<span class="text-green-600">Événement créé avec succès.</span>');

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('alert', '<span class="text-red-600">Erreur de validation. Veuillez corriger les champs indiqués.</span>');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('alert', '<span class="text-red-600">Une erreur est survenue : ' . e($e->getMessage()) . '</span>');
        }
    }

    public function show(Evenement $evenement)
    {
        return view('admin.evenements.show', compact('evenement'));
    }

    public function edit(Evenement $evenement)
    {
        return view('admin.evenements.edit', compact('evenement'));
    }

    public function update(Request $request, Evenement $evenement)
    {
        try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'resume' => 'nullable|string|max:500',
                'description' => 'required|string',
                'date_debut' => 'required|date',
                'date_fin' => 'nullable|date|after_or_equal:date_debut',
                'lieu' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
                'rapport_url' => 'nullable|url|max:255',
            ]);

            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image
                if ($evenement->image && Storage::disk('public')->exists($evenement->image)) {
                    Storage::disk('public')->delete($evenement->image);
                }

                $filename = 'evenements/' . uniqid() . '_' . preg_replace('/\s+/', '_', $request->file('image')->getClientOriginalName());
                $path = $request->file('image')->storeAs('assets/images', $filename, 'public');
                $validated['image'] = $path;
            }

            $evenement->update($validated);

            return redirect()->route('admin.evenements.index')
                ->with('alert', '<span class="text-green-600">Événement mis à jour avec succès.</span>');

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('alert', '<span class="text-red-600">Erreur de validation. Veuillez corriger les champs indiqués.</span>');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('alert', '<span class="text-red-600">Une erreur est survenue lors de la mise à jour : ' . e($e->getMessage()) . '</span>');
        }
    }

    public function destroy(Evenement $evenement)
    {
        try {
            // Supprimer l'image associée
            if ($evenement->image && Storage::disk('public')->exists($evenement->image)) {
                Storage::disk('public')->delete($evenement->image);
            }

            $evenement->delete();

            return redirect()->route('admin.evenements.index')
                ->with('alert', '<span class="text-green-600">Événement supprimé avec succès.</span>');

        } catch (\Exception $e) {
            return back()
                ->with('alert', '<span class="text-red-600">Erreur lors de la suppression : ' . e($e->getMessage()) . '</span>');
        }
    }

    /**
     * Publier un événement
     */
    public function publish(Request $request, Evenement $evenement)
    {
        try {
            $evenement->update([
                'is_published' => true,
                'published_at' => now(),
                'published_by' => auth()->id(),
                'moderation_comment' => $request->input('comment')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Événement publié avec succès',
                'published_at' => $evenement->published_at ? $evenement->published_at->format('d/m/Y H:i') : null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la publication : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dépublier un événement
     */
    public function unpublish(Request $request, Evenement $evenement)
    {
        try {
            $evenement->update([
                'is_published' => false,
                'published_at' => null,
                'published_by' => null,
                'moderation_comment' => $request->input('comment')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Événement dépublié avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la dépublication : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Liste des événements en attente de modération
     */
    public function pendingModeration()
    {
        $evenements = Evenement::where('is_published', false)
                              ->latest('created_at')
                              ->paginate(15);

        return view('admin.evenements.pending', compact('evenements'));
    }
}
