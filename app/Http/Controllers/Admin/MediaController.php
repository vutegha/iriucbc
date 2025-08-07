<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class MediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
public function index(Request $request)
{
    
        $this->authorize('viewAny', Media::class);
// Vérification des permissions
    Gate::authorize('viewAny', Media::class);

    $query = Media::with(['creator', 'moderator', 'projet']);

    // Filtrage par type
    if ($request->has('type') && in_array($request->type, ['image', 'video'])) {
        $query->where('type', $request->type);
    }

    // Filtrage par statut
    if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'rejected', 'published'])) {
        $query->where('status', $request->status);
    }

    // Recherche par titre ou description
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('titre', 'LIKE', '%' . $search . '%')
              ->orWhere('description', 'LIKE', '%' . $search . '%');
        });
    }

    // Si l'utilisateur n'est pas admin, il ne voit que ses médias
    if (!Auth::user()->hasAnyRole(['super-admin', 'admin'])) {
        $query->where('created_by', Auth::id());
    }

    $medias = $query->latest()->paginate(12);

    // Statistiques des médias
    $stats = [
        'total' => Media::count(),
        'images' => Media::where('type', 'image')->count(),
        'videos' => Media::where('type', 'video')->count(),
        'published' => Media::where('status', 'published')->count(),
        'pending' => Media::where('status', 'pending')->count(),
        'approved' => Media::where('status', 'approved')->count(),
        'rejected' => Media::where('status', 'rejected')->count(),
    ];

    // Statistiques par type et statut
    $imageStats = [
        'total' => Media::where('type', 'image')->count(),
        'published' => Media::where('type', 'image')->where('status', 'published')->count(),
        'pending' => Media::where('type', 'image')->where('status', 'pending')->count(),
    ];

    $videoStats = [
        'total' => Media::where('type', 'video')->count(),
        'published' => Media::where('type', 'video')->where('status', 'published')->count(),
        'pending' => Media::where('type', 'video')->where('status', 'pending')->count(),
    ];

    return view('admin.media.index', compact('medias', 'stats', 'imageStats', 'videoStats'));
}

    public function show(Media $media)
    {
        
        $this->authorize('view', $Media);
Gate::authorize('view', $media);
        
        $media->load(['creator', 'moderator', 'projet']);
        
        return view('admin.media.show', compact('media'));
    }

    public function create()
    {
        
        $this->authorize('create', Media::class);
Gate::authorize('create', Media::class);
        
        $projets = \App\Models\Projet::all();
        return view('admin.media.create', compact('projets'));
    }

public function store(Request $request)
{
    
        $this->authorize('create', Media::class);
$validated = $request->validate([
        'type' => 'nullable|string|max:255',
        'titre' => 'nullable|string|max:255',
        'medias' => 'nullable|file|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg,video/mp4,video/quicktime,video/webm|max:40480',
        'projet_id' => 'nullable|integer|exists:projets,id',
    ]);

    try {
        // Upload du fichier si présent
        if ($request->hasFile('medias')) {
            $path = $request->file('medias')->store('assets/media', 'public');
            $validated['medias'] = $path;
        }

        Media::create($validated);

        return redirect()->route('admin.media.index')->with('success', 'Média enregistré avec succès.');
    } catch (\Exception $e) {
        return back()->withErrors(['message' => 'Erreur lors de l’enregistrement : ' . $e->getMessage()])
                     ->withInput();
    }
}




    public function edit(Media $media)
    {
        
        $this->authorize('update', $Media);
$projets = \App\Models\Projet::all();
        return view('admin.media.edit', ['media' => $media, 'projets' => $projets]);
    }

public function update(Request $request, Media $media)
{
    
        $this->authorize('update', $Media);
$validated = $request->validate([
        'type' => 'nullable|string|max:255',
        'titre' => 'nullable|string|max:255',
        'medias' => 'nullable|file|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg,video/mp4,video/quicktime,video/webm|max:40480',
        'projet_id' => 'nullable|integer|exists:projets,id',
    ]);

    try {
        // Remplacer le média s’il y a un nouveau fichier
        if ($request->hasFile('medias')) {
            // Supprimer l'ancien fichier
            if ($media->medias && Storage::disk('public')->exists($media->medias)) {
                Storage::disk('public')->delete($media->medias);
            }

            // Uploader le nouveau
            $path = $request->file('medias')->store('assets/media', 'public');
            $validated['medias'] = $path;
        }

        $media->update($validated);

        return redirect()->route('admin.media.index')->with('success', 'Média mis à jour avec succès.');
    } catch (\Exception $e) {
        return back()->withErrors(['message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()])
                     ->withInput();
    }
}




public function destroy(Media $media)
{
    
        $this->authorize('delete', $Media);
try {
        if ($media->medias && Storage::disk('public')->exists($media->medias)) {
            Storage::disk('public')->delete($media->medias);
        }

        $media->delete();

        return redirect()->route('admin.media.index')->with('success', 'Média supprimé avec succès.');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors([
            'message' => 'Erreur lors de la suppression : ' . $e->getMessage(),
        ]);
    }
}

/**
 * Liste les médias pour CKEditor (format JSON)
 */
public function list()
{
    $medias = Media::where(function($query) {
                       $query->where('type', 'image')
                             ->orWhere('medias', 'like', '%.jpg')
                             ->orWhere('medias', 'like', '%.jpeg')
                             ->orWhere('medias', 'like', '%.png')
                             ->orWhere('medias', 'like', '%.gif')
                             ->orWhere('medias', 'like', '%.webp');
                   })
                   ->latest()
                   ->get()
                   ->map(function ($media) {
                       return [
                           'id' => $media->id,
                           'url' => asset('storage/' . $media->medias),
                           'name' => $media->titre ?: basename($media->medias),
                           'alt' => $media->titre ?: 'Image'
                       ];
                   });

    return response()->json($medias);
}

/**
 * Upload d'image pour CKEditor
 */
public function upload(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240'
    ]);

    try {
        $file = $request->file('image');
        $path = $file->store('assets/media', 'public');
        
        // Créer l'entrée en base
        $media = Media::create([
            'type' => 'image',
            'titre' => $file->getClientOriginalName(),
            'medias' => $path,
        ]);

        return response()->json([
            'success' => true,
            'id' => $media->id,
            'url' => asset('storage/' . $path),
            'name' => $file->getClientOriginalName(),
            'message' => 'Image uploadée avec succès'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'upload : ' . $e->getMessage()
        ], 500);
    }
    }

    /**
     * Actions de modération
     */
    public function approve(Media $media)
    {
        Gate::authorize('approve', $media);
        
        $media->update([
            'status' => Media::STATUS_APPROVED,
            'moderated_by' => Auth::id(),
            'moderated_at' => now()
        ]);
        
        Log::info('Média approuvé', [
            'media_id' => $media->id,
            'moderator_id' => Auth::id()
        ]);
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Média approuvé avec succès.'
            ]);
        }
        
        return back()->with('success', 'Média approuvé avec succès.');
    }
    
    public function reject(Request $request, Media $media)
    {
        Gate::authorize('reject', $media);
        
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);
        
        $media->update([
            'status' => Media::STATUS_REJECTED,
            'moderated_by' => Auth::id(),
            'moderated_at' => now(),
            'rejection_reason' => $request->rejection_reason
        ]);
        
        Log::info('Média rejeté', [
            'media_id' => $media->id,
            'moderator_id' => Auth::id(),
            'reason' => $request->rejection_reason
        ]);
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Média rejeté.'
            ]);
        }
        
        return back()->with('success', 'Média rejeté.');
    }
    
    public function publish(Media $media)
    {
        Gate::authorize('publish', $media);
        
        $media->update([
            'status' => Media::STATUS_PUBLISHED,
            'is_public' => true
        ]);
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Média publié avec succès.'
            ]);
        }
        
        return back()->with('success', 'Média publié avec succès.');
    }
    
    public function unpublish(Media $media)
    {
        Gate::authorize('publish', $media);
        
        $media->update([
            'status' => Media::STATUS_APPROVED,
            'is_public' => false
        ]);
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Média dépublié.'
            ]);
        }
        
        return back()->with('success', 'Média dépublié.');
    }
    
    /**
     * Copier le lien du média
     */
    public function copyLink(Media $media)
    {
        Gate::authorize('copyLink', $media);
        
        $url = asset('storage/' . $media->medias);
        
        return response()->json([
            'success' => true,
            'url' => $url,
            'message' => 'Lien copié dans le presse-papiers'
        ]);
    }
    
    /**
     * Téléchargement sécurisé
     */
    public function download(Media $media)
    {
        Gate::authorize('download', $media);
        
        $filePath = storage_path('app/public/' . $media->medias);
        
        if (!file_exists($filePath)) {
            abort(404, 'Fichier non trouvé');
        }
        
        Log::info('Téléchargement média', [
            'media_id' => $media->id,
            'user_id' => Auth::id()
        ]);
        
        return response()->download($filePath, $media->titre . '.' . $media->file_extension);
    }

}