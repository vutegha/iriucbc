<?php  

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::latest()->get();
        return view('admin.service.index', compact('services'));
    }

    public function create()
    {
        return view('admin.service.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'nom_menu' => 'nullable|string|max:255',
            'resume' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('services/images', 'public');
        }

        Service::create($validated);

        return redirect()->route('admin.service.index')->with('success', 'Service ajouté.');
    }

    public function edit(Service $service)
    {
        return view('admin.service.edit', compact('service'));
    }

    public function show(Service $service)
    {
        return view('admin.service.show', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'nom_menu' => 'nullable|string|max:255',
            'resume' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $validated['image'] = $request->file('image')->store('services/images', 'public');
        }

        $service->update($validated);

        return redirect()->route('admin.service.index')->with('success', 'Service mis à jour.');
    }

    public function destroy(Service $service)
    {
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }
        $service->delete();

        return redirect()->route('admin.service.index')->with('success', 'Service supprimé.');
    }

    /**
     * Publier un service
     */
    public function publish(Request $request, Service $service)
    {
        try {
            $service->publish(auth()->user(), $request->input('comment'));
            
            return response()->json([
                'success' => true,
                'message' => 'Service publié avec succès',
                'status' => $service->publication_status,
                'published_at' => $service->published_at ? $service->published_at->format('d/m/Y H:i') : null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la publication : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dépublier un service
     */
    public function unpublish(Request $request, Service $service)
    {
        try {
            $service->unpublish($request->input('comment'));
            
            return response()->json([
                'success' => true,
                'message' => 'Service dépublié avec succès',
                'status' => $service->publication_status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la dépublication : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Basculer l'affichage dans le menu
     */
    public function toggleMenu(Request $request, Service $service)
    {
        try {
            $service->toggleMenu();
            
            return response()->json([
                'success' => true,
                'message' => $service->show_in_menu ? 'Service ajouté au menu' : 'Service retiré du menu',
                'show_in_menu' => $service->show_in_menu
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Voir les éléments en attente de modération
     */
    public function pendingModeration()
    {
        $services = Service::pendingModeration()
                          ->latest()
                          ->paginate(10);

        return view('admin.service.pending', compact('services'));
    }
}
