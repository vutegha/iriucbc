<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rapport;
use App\Models\Categorie;
use App\Events\RapportCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RapportController extends Controller
{
    public function index(Request $request)
    {
        
        $this->authorize('viewAny', Rapport::class);
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
        
        $this->authorize('create', Rapport::class);
$categories = Categorie::all();
        return view('admin.rapports.create', compact('categories'));
    }

    public function store(Request $request)
    {
        
        $this->authorize('create', Rapport::class);
try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'description' => 'nullable|string',
                'date_publication' => 'nullable|date',
                'categorie_id' => 'nullable|exists:categories,id',
                'fichier' => 'required|file|mimes:pdf,doc,docx|max:51200', // max 50MB
                'is_published' => 'nullable|boolean',
            ]);

            // Traitement du checkbox is_published
            $validated['is_published'] = $request->has('is_published') && $request->is_published;
            
            // Si publié par un modérateur, définir les timestamps
            if ($validated['is_published'] && auth()->user()->canModerate()) {
                $validated['published_at'] = now();
                $validated['published_by'] = auth()->id();
            }

            // Enregistrement du fichier
            if ($request->hasFile('fichier')) {
                $filename = uniqid() . '_' . Str::slug(pathinfo($request->file('fichier')->getClientOriginalName(), PATHINFO_FILENAME));
                $extension = $request->file('fichier')->getClientOriginalExtension();
                $path = $request->file('fichier')->storeAs('assets/rapports', "$filename.$extension", 'public');
                $validated['fichier'] = $path;
            }

            $rapport = Rapport::create($validated);

            // NOTE: L'événement RapportCreated sera déclenché uniquement 
            // lors de l'action de modération "publier" pour respecter le workflow

            return redirect()->route('admin.rapports.index')
                ->with('alert', '<span class="alert alert-success">Rapport enregistré avec succès.</span>');
        } catch (\Exception $e) {
            return back()->withInput()->with('alert', '<span class="alert alert-danger">Erreur : ' . e($e->getMessage()) . '</span>');
        }
    }

    public function edit(Rapport $rapport)
    {
        
        $this->authorize('update', $Rapport);
$categories = Categorie::all();
        return view('admin.rapports.edit', compact('rapport', 'categories'));
    }

    public function update(Request $request, Rapport $rapport)
    {
        
        $this->authorize('update', $Rapport);
try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'description' => 'nullable|string',
                'date_publication' => 'nullable|date',
                'categorie_id' => 'nullable|exists:categories,id',
                'fichier' => 'nullable|file|mimes:pdf,doc,docx|max:51200', // max 50MB
                'is_published' => 'nullable|boolean',
            ]);

            // Traitement du checkbox is_published
            $validated['is_published'] = $request->has('is_published') && $request->is_published;
            
            // Si publié par un modérateur, définir les timestamps
            if ($validated['is_published'] && auth()->user()->canModerate() && !$rapport->published_at) {
                $validated['published_at'] = now();
                $validated['published_by'] = auth()->id();
            }

            // Génération du slug unique
            if ($request->filled('titre') && $request->titre !== $rapport->titre) {
                $baseSlug = Str::slug($validated['titre']);
                $slug = $baseSlug;
                $counter = 1;
                
                while (Rapport::where('slug', $slug)->where('id', '!=', $rapport->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                
                $validated['slug'] = $slug;
            }

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

            // NOTE: L'événement RapportCreated sera déclenché uniquement 
            // lors de l'action de modération "publier" pour respecter le workflow

            return redirect()->route('admin.rapports.index')
                ->with('alert', '<span class="alert alert-success">Rapport mis à jour avec succès.</span>');
        } catch (\Exception $e) {
            return back()->withInput()->with('alert', '<span class="alert alert-danger">Erreur : ' . e($e->getMessage()) . '</span>');
        }
    }

    public function show(Rapport $rapport)
    {
        
        $this->authorize('view', $Rapport);
return view('admin.rapports.show', compact('rapport'));
    }

    public function destroy(Rapport $rapport)
    {
        
        $this->authorize('delete', $Rapport);
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
            
            // Déclencher l'événement newsletter lors de la publication officielle
            try {
                RapportCreated::dispatch($rapport);
                \Log::info('Événement RapportCreated déclenché lors de la publication', [
                    'rapport_id' => $rapport->id,
                    'titre' => $rapport->titre
                ]);
            } catch (\Exception $e) {
                \Log::warning('Erreur lors du déclenchement de l\'événement RapportCreated', [
                    'rapport_id' => $rapport->id,
                    'error' => $e->getMessage()
                ]);
            }
            
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

    /**
     * Supprimer plusieurs rapports en une fois
     */
    public function deleteMultiple(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:rapports,id'
            ]);

            $deletedCount = 0;
            foreach ($validated['ids'] as $id) {
                $rapport = Rapport::find($id);
                if ($rapport) {
                    // Supprimer le fichier s'il existe
                    if ($rapport->fichier && file_exists(public_path($rapport->fichier))) {
                        unlink(public_path($rapport->fichier));
                    }
                    $rapport->delete();
                    $deletedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "{$deletedCount} rapport(s) supprimé(s) avec succès"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exporter plusieurs rapports en Excel
     */
    public function export(Request $request)
    {
        try {
            $ids = explode(',', $request->get('ids', ''));
            $rapports = Rapport::whereIn('id', $ids)->with('categorie')->get();

            // Créer les données pour l'export
            $exportData = [];
            foreach ($rapports as $rapport) {
                $exportData[] = [
                    'ID' => $rapport->id,
                    'Titre' => $rapport->titre,
                    'Description' => $rapport->description,
                    'Catégorie' => $rapport->categorie->nom ?? 'N/A',
                    'Date de publication' => $rapport->date_publication ? $rapport->date_publication->format('d/m/Y') : 'N/A',
                    'Fichier' => $rapport->fichier ? basename($rapport->fichier) : 'Aucun',
                    'Créé le' => $rapport->created_at->format('d/m/Y H:i'),
                    'Modifié le' => $rapport->updated_at->format('d/m/Y H:i'),
                ];
            }

            // Utiliser une simple réponse CSV pour l'instant
            $filename = 'rapports_export_' . date('Y-m-d_H-i-s') . '.csv';
            
            $output = fopen('php://output', 'w');
            
            // Headers pour le téléchargement
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            // En-têtes CSV
            if (!empty($exportData)) {
                fputcsv($output, array_keys($exportData[0]));
                foreach ($exportData as $row) {
                    fputcsv($output, $row);
                }
            }
            
            fclose($output);
            exit;
            
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'export: ' . $e->getMessage());
        }
    }

    /**
     * Télécharger plusieurs rapports dans un fichier ZIP
     */
    public function downloadZip(Request $request)
    {
        try {
            $ids = explode(',', $request->get('ids', ''));
            $rapports = Rapport::whereIn('id', $ids)->get();

            $zipFilename = 'rapports_' . date('Y-m-d_H-i-s') . '.zip';
            $zipPath = storage_path('app/temp/' . $zipFilename);
            
            // Créer le dossier temp s'il n'existe pas
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE) !== TRUE) {
                throw new \Exception('Impossible de créer le fichier ZIP');
            }

            foreach ($rapports as $rapport) {
                if ($rapport->fichier && file_exists(public_path($rapport->fichier))) {
                    $filename = Str::slug($rapport->titre) . '_' . $rapport->id . '.' . pathinfo($rapport->fichier, PATHINFO_EXTENSION);
                    $zip->addFile(public_path($rapport->fichier), $filename);
                }
            }

            $zip->close();

            // Télécharger et supprimer le fichier temporaire
            return response()->download($zipPath, $zipFilename)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création du ZIP: ' . $e->getMessage());
        }
    }
}
