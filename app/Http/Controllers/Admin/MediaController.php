<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
public function index(Request $request)
{
    $query = Media::query();

    if ($request->has('type') && in_array($request->type, ['image', 'video'])) {
        $query->where('type', $request->type);
    }

    $medias = $query->latest()->get(); // ou paginate()

    return view('admin.media.index', compact('medias'));
}

    public function create()
    {
        $projets = \App\Models\Projet::all();
        return view('admin.media.create', compact('projets'));
    }

public function store(Request $request)
{
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
        $projets = \App\Models\Projet::all();
        return view('admin.media.edit', ['media' => $media, 'projets' => $projets]);
    }

public function update(Request $request, Media $media)
{
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
    $medias = Media::where('type', 'image')
                   ->orWhere('medias', 'like', '%.jpg')
                   ->orWhere('medias', 'like', '%.jpeg')
                   ->orWhere('medias', 'like', '%.png')
                   ->orWhere('medias', 'like', '%.gif')
                   ->orWhere('medias', 'like', '%.webp')
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



}
