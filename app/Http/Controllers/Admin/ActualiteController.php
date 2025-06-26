<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actualite;
use App\Models\Categorie;
use App\Models\Auteur;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ActualiteController extends Controller
{
public function index(Request $request)
{
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
        return view('admin.actualite.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'resume' => 'nullable|string|max:255',
                'texte' => 'required|string',
                'a_la_une' => 'nullable|boolean',
                'en_vedette' => 'nullable|boolean',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            ]);


            $validated['a_la_une'] = $request->has('a_la_une') ? 1 : 0;
            $validated['en_vedette'] = $request->has('en_vedette') ? 1 : 0;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = uniqid('img_') . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('assets/images', $filename, 'public');
                $validated['image'] = $path;
            }

            Actualite::create($validated);

            return redirect()->route('admin.actualite.index')
                ->with('alert', '<span class="alert alert-success">Actualité enregistrée avec succès.</span>');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('alert', '<span class="alert alert-danger">Erreur : ' . e($e->getMessage()) . '</span>');
        }
    }

    public function edit(Actualite $actualite)
    {
        return view('admin.actualite.edit', compact('actualite'));
    }

    public function update(Request $request, Actualite $actualite)
    {
        try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'resume' => 'nullable|string|max:255',
                'texte' => 'required|string',
                'a_la_une' => 'nullable|boolean',
                'en_vedette' => 'nullable|boolean',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            ]);

            $validated['a_la_une'] = $request->has('a_la_une') ? 1 : 0;
            $validated['en_vedette'] = $request->has('en_vedette') ? 1 : 0;

            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image
                if ($actualite->image && Storage::disk('public')->exists($actualite->image)) {
                    Storage::disk('public')->delete($actualite->image);
                }

                $filename = uniqid('img_') . '.' . $request->file('image')->getClientOriginalExtension();
                $path = $request->file('image')->storeAs('assets/images', $filename, 'public');
                $validated['image'] = $path;
            }

            $actualite->update($validated);

            return redirect()->route('admin.actualite.index')
                ->with('alert', '<span class="alert alert-success">Actualité mise à jour avec succès.</span>');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('alert', '<span class="alert alert-danger">Erreur : ' . e($e->getMessage()) . '</span>');
        }
    }

    public function show(Actualite $actualite)
    {
        return view('admin.actualite.show', compact('actualite'));
    }

    public function destroy(Actualite $actualite)
    {
        try {
            if ($actualite->image && Storage::disk('public')->exists($actualite->image)) {
                Storage::disk('public')->delete($actualite->image);
            }

            $actualite->delete();

            return redirect()->route('admin.actualite.index')
                ->with('alert', '<span class="alert alert-success">Actualité supprimée avec succès.</span>');
        } catch (\Exception $e) {
            return back()->with('alert', '<span class="alert alert-danger">Erreur : ' . e($e->getMessage()) . '</span>');
        }
    }
}
