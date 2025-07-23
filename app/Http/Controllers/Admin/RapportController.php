<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rapport;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RapportController extends Controller
{
    public function index(Request $request)
    {
        $categories = Categorie::all();
        $annees = Rapport::selectRaw('YEAR(date_publication) as annee')->distinct()->orderByDesc('annee')->pluck('annee')->toArray();

        $rapports = Rapport::query()
                            ->when($request->categorie, fn($q) => $q->where('categorie_id', $request->categorie))
                            ->when($request->annee, fn($q) => $q->whereYear('date_publication', $request->annee))
                            ->latest()
                            ->paginate(10);

        return view('admin.rapports.index', compact('rapports', 'categories', 'annees', 'request'));
    }

    public function create()
    {
        $categories = Categorie::all();
        return view('admin.rapports.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'resume' => 'nullable|string',
                'date_publication' => 'nullable|date',
                'categorie_id' => 'nullable|exists:categories,id',
                'fichier' => 'required|file|mimes:pdf,doc,docx|max:20480', // max 20MB
            ]);


            // Enregistrement du fichier
            if ($request->hasFile('fichier')) {
                $filename = uniqid() . '_' . Str::slug(pathinfo($request->file('fichier')->getClientOriginalName(), PATHINFO_FILENAME));
                $extension = $request->file('fichier')->getClientOriginalExtension();
                $path = $request->file('fichier')->storeAs('assets/rapports', "$filename.$extension", 'public');
                $validated['fichier'] = $path;
            }

            Rapport::create($validated);

            return redirect()->route('admin.rapports.index')
                ->with('alert', '<span class="alert alert-success">Rapport enregistré avec succès.</span>');
        } catch (\Exception $e) {
            return back()->withInput()->with('alert', '<span class="alert alert-danger">Erreur : ' . e($e->getMessage()) . '</span>');
        }
    }

    public function edit(Rapport $rapport)
    {
        $categories = Categorie::all();
        return view('admin.rapports.edit', compact('rapport', 'categories'));
    }

    public function update(Request $request, Rapport $rapport)
    {
        try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'resume' => 'nullable|string',
                'date_publication' => 'nullable|date',
                'categorie_id' => 'nullable|exists:categories,id',
                'fichier' => 'nullable|file|mimes:pdf,doc,docx|max:20480',
            ]);

            $validated['slug'] = Str::slug($validated['titre']);

            if ($request->hasFile('fichier')) {
                if ($rapport->fichier && Storage::disk('public')->exists($rapport->fichier)) {
                    Storage::disk('public')->delete($rapport->fichier);
                }

                $filename = uniqid() . '_' . Str::slug(pathinfo($request->file('fichier')->getClientOriginalName(), PATHINFO_FILENAME));
                $extension = $request->file('fichier')->getClientOriginalExtension();
                $path = $request->file('fichier')->storeAs('assets/rapports', "$filename.$extension", 'public');
                $validated['fichier'] = $path;
            }

            $rapport->update($validated);

            return redirect()->route('admin.rapports.index')
                ->with('alert', '<span class="alert alert-success">Rapport mis à jour avec succès.</span>');
        } catch (\Exception $e) {
            return back()->withInput()->with('alert', '<span class="alert alert-danger">Erreur : ' . e($e->getMessage()) . '</span>');
        }
    }

    public function show(Rapport $rapport)
    {
        return view('admin.rapports.show', compact('rapport'));
    }

    public function destroy(Rapport $rapport)
    {
        if ($rapport->fichier && Storage::disk('public')->exists($rapport->fichier)) {
            Storage::disk('public')->delete($rapport->fichier);
        }

        $rapport->delete();

        return redirect()->route('admin.rapports.index')
            ->with('alert', '<span class="alert alert-success">Rapport supprimé avec succès.</span>');
    }

    /**
     * Publier un rapport
     */
    public function publish(Request $request, Rapport $rapport)
    {
        try {
            $rapport->publish(auth()->user(), $request->input('comment'));
            
            return response()->json([
                'success' => true,
                'message' => 'Rapport publié avec succès',
                'status' => $rapport->publication_status,
                'published_at' => $rapport->published_at ? $rapport->published_at->format('d/m/Y H:i') : null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la publication : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dépublier un rapport
     */
    public function unpublish(Request $request, Rapport $rapport)
    {
        try {
            $rapport->unpublish($request->input('comment'));
            
            return response()->json([
                'success' => true,
                'message' => 'Rapport dépublié avec succès',
                'status' => $rapport->publication_status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la dépublication : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Voir les éléments en attente de modération
     */
    public function pendingModeration()
    {
        $rapports = Rapport::pendingModeration()
                          ->with(['categorie'])
                          ->latest()
                          ->paginate(10);

        return view('admin.rapports.pending', compact('rapports'));
    }
}
