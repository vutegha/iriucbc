<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Projet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\service;
use App\Events\ProjectCreated;

class ProjetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:viewAny,App\Models\Projet')->only(['index']);
        $this->middleware('can:view,projet')->only(['show']);
        $this->middleware('can:create,App\Models\Projet')->only(['create', 'store']);
        $this->middleware('can:update,projet')->only(['edit', 'update']);
        $this->middleware('can:delete,projet')->only(['destroy']);
        $this->middleware('can:moderate,projet')->only(['publish', 'unpublish']);
    }

   public function index(Request $request)
{
    $query = Projet::with('service');

    // Recherche textuelle
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('nom', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('resume', 'like', "%{$search}%");
        });
    }

    // Filtre par état
    if ($request->filled('etat')) {
        $query->where('etat', $request->etat);
    }

    // Filtre par publication
    if ($request->filled('is_published')) {
        $query->where('is_published', $request->is_published == '1');
    }

    // Filtre par service
    if ($request->filled('service_id')) {
        $query->where('service_id', $request->service_id);
    }

    // Filtre par année
    if ($request->filled('annee')) {
        $query->whereYear('date_debut', $request->annee);
    }

    // Filtre par dates de création
    if ($request->filled('created_at_from')) {
        $query->whereDate('created_at', '>=', $request->created_at_from);
    }

    if ($request->filled('created_at_to')) {
        $query->whereDate('created_at', '<=', $request->created_at_to);
    }

    $projets = $query->latest()->paginate(12);

    // Pour le filtre année
    $anneesDisponibles = Projet::selectRaw('YEAR(date_debut) as annee')
                                ->whereNotNull('date_debut')
                                ->distinct()
                                ->orderBy('annee', 'desc')
                                ->pluck('annee')
                                ->toArray();

    // Calcul du budget total
    $budgetTotal = Projet::whereNotNull('budget')->sum('budget');

    // Services pour les filtres
    $services = Service::orderBy('nom')->get();

    return view('admin.projets.index', compact('projets', 'anneesDisponibles', 'budgetTotal', 'services'));
}


    public function create()
    {
        $services=Service::All();
        return view('admin.projets.create', compact('services'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'description' => 'required|string',
                'date_debut' => 'nullable|date',
                'service_id' => 'required|exists:services,id',
                'resume'=>'nullable| string|max:255',
                'date_fin' => 'nullable|date|after_or_equal:date_debut',
                'etat' => 'required|string|in:en cours,terminé,suspendu',
                'budget' => 'nullable|numeric|min:0',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5048',
                'beneficiaires_hommes' => 'nullable|integer|min:0',
                'beneficiaires_femmes' => 'nullable|integer|min:0',
                'beneficiaires_enfants' => 'nullable|integer|min:0',
                'beneficiaires_total' => 'nullable|integer|min:0',
            ]);

            if ($request->hasFile('image')) {
                $filename = 'projets/' . uniqid() . '_' . preg_replace('/\s+/', '_', $request->file('image')->getClientOriginalName());
                $path = $request->file('image')->storeAs('assets/images', $filename, 'public');
                $validated['image'] = $path;
            }

            $projet = Projet::create($validated);

            // Déclencher l'événement pour tout nouveau projet
            ProjectCreated::dispatch($projet);

            return redirect()->route('admin.projets.index')
                ->with('alert', '<span class="text-green-600">Projet enregistré avec succès.</span>');

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('alert', '<span class="text-red-600">Erreur de validation. Veuillez corriger les champs indiqués.</span>');
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('alert', '<span class="text-red-600">Une erreur est survenue : ' . e($e->getMessage()) . '</span>');
        }
    }

    public function edit(Projet $projet)
    {
        $services=Service::All();
        return view('admin.projets.edit', compact('projet','services'));
    }

    public function update(Request $request, Projet $projet)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5048',
                'service_id' => 'required|exists:services,id',
                'resume'=>'nullable| string|max:255',
                'date_debut' => 'nullable|date',
                'date_fin' => 'nullable|date|after_or_equal:date_debut',
                'etat' => 'required|in:en cours,terminé,suspendu',
                'budget' => 'nullable|numeric|min:0',
                'beneficiaires_hommes' => 'nullable|integer|min:0',
                'beneficiaires_femmes' => 'nullable|integer|min:0',
                'beneficiaires_total' => 'nullable|integer|min:0',
            ]);

            if ($request->hasFile('image')) {
                if ($projet->image && Storage::disk('public')->exists($projet->image)) {
                    Storage::disk('public')->delete($projet->image);
                }

                $filename = 'projets/' . uniqid() . '_' . preg_replace('/\s+/', '_', $request->file('image')->getClientOriginalName());
                $path = $request->file('image')->storeAs('assets/images', $filename, 'public');
                $validated['image'] = $path;
            }

            $projet->update($validated);

            return redirect()->route('admin.projets.index')
                ->with('alert', '<span class="text-green-600">Projet mis à jour avec succès.</span>');

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('alert', '<span class="text-red-600">Erreur de validation. Veuillez corriger les champs indiqués.</span>');
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('alert', '<span class="text-red-600">Une erreur est survenue lors de la mise à jour : ' . e($e->getMessage()) . '</span>');
        }
    }

    public function show(Projet $projet)
    {
        $projet->load(['medias', 'publishedBy']);
        return view('admin.projets.show', compact('projet'));
    }

    public function destroy(Projet $projet)
    {
        try {
            if ($projet->image && Storage::disk('public')->exists($projet->image)) {
                Storage::disk('public')->delete($projet->image);
            }

            $projet->delete();

            return redirect()->route('admin.projets.index')
                ->with('alert', '<span class="text-green-600">Projet supprimé avec succès.</span>');

        } catch (\Exception $e) {
            return back()
                ->with('alert', '<span class="text-red-600">Erreur lors de la suppression : ' . e($e->getMessage()) . '</span>');
        }
    }

    /**
     * Publier un projet
     */
    public function publish(Request $request, Projet $projet)
    {
        $this->authorize('moderate', $projet);
        
        try {
            $projet->update([
                'is_published' => true,
                'published_at' => now(),
                'published_by' => auth()->id(),
                'moderation_comment' => $request->input('comment')
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Projet publié avec succès'
                ]);
            }
            
            return redirect()->route('admin.projets.show', $projet)
                ->with('alert', '<span class="text-green-600">✅ Projet publié avec succès ! Il est maintenant visible par le public.</span>');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la publication : ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.projets.show', $projet)
                ->with('alert', '<span class="text-red-600">❌ Erreur lors de la publication : ' . e($e->getMessage()) . '</span>');
        }
    }

    /**
     * Dépublier un projet
     */
    public function unpublish(Request $request, Projet $projet)
    {
        $this->authorize('moderate', $projet);
        
        try {
            $projet->update([
                'is_published' => false,
                'published_at' => null,
                'published_by' => null,
                'moderation_comment' => $request->input('comment')
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Projet dépublié avec succès'
                ]);
            }
            
            return redirect()->route('admin.projets.show', $projet)
                ->with('alert', '<span class="text-orange-600">⚠️ Projet dépublié avec succès ! Il n\'est plus visible par le public.</span>');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la dépublication : ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.projets.show', $projet)
                ->with('alert', '<span class="text-red-600">❌ Erreur lors de la dépublication : ' . e($e->getMessage()) . '</span>');
        }
    }

    /**
     * Voir les éléments en attente de modération
     */
    public function pendingModeration()
    {
        $projets = Projet::pendingModeration()
                        ->with(['service'])
                        ->latest()
                        ->paginate(10);

        return view('admin.projets.pending', compact('projets'));
    }
}
