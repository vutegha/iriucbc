<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use App\Models\Publication;
use App\Models\Auteur;
use App\Models\Categorie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PublicationController extends Controller
{
public function index(Request $request)
{
    $query = \App\Models\Publication::with('auteur', 'categorie');

    if ($request->filled('auteur')) {
        $query->where('auteur_id', $request->auteur);
    }

    if ($request->filled('categorie')) {
        $query->where('categorie_id', $request->categorie);
    }

    $publications = $query->latest()->paginate(10)->appends($request->query());

    $auteurs = \App\Models\Auteur::all();
    $categories = \App\Models\Categorie::all();

    return view('admin.publication.index', compact('publications', 'auteurs', 'categories', 'request'));
}


   

    public function create()
    {
        $auteurs = \App\Models\Auteur::all();
        $categories = \App\Models\Categorie::all();
        return view('admin.publication.create', compact('auteurs', 'categories'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'resume' => 'required|string',
                'citation' => 'nullable|string',
                'a_la_une' => 'nullable|boolean',
                'en_vedette' => 'nullable|boolean',
                'auteur_id' => 'required|exists:auteurs,id',
                'categorie_id' => 'required|exists:categories,id',
                'fichier_pdf' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,odt,odp|max:40240',
            ]);

            // Ensure boolean fields are set to false if not present
            $validated['a_la_une'] = $request->has('a_la_une') ? 1 : 0;
            $validated['en_vedette'] = $request->has('en_vedette') ? 1 : 0;

            if ($request->hasFile('fichier_pdf')) {
                // Générer le nom du fichier à partir du titre en remplaçant les espaces par des underscores
                $filename = preg_replace('/\s+/', '_', $validated['titre']) . '.' . $request->file('fichier_pdf')->getClientOriginalExtension();
                $path = $request->file('fichier_pdf')->storeAs('assets', $filename, 'public');
                $validated['fichier_pdf'] = $path;
            }

            Publication::create($validated);
            return redirect()->route('admin.publication.index')
                ->with('alert', '<span class="alert alert-success">Publication enregistrée avec succès.</span>');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('alert', '<span class="alert alert-danger">Erreur de validation. Veuillez vérifier les champs.</span>');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('alert', '<span class="alert alert-danger">Une erreur est survenue lors de l\'enregistrement : ' . e($e->getMessage()) . '</span>')
                ->withInput();
        }
    }

public function edit(Publication $publication)
{
    $auteurs = \App\Models\Auteur::all();
    $categories = \App\Models\Categorie::all();

    return view('admin.publication.edit', compact('publication', 'auteurs', 'categories'));
}



public function update(Request $request, Publication $publication)
{
    try {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'resume' => 'required|string',
            'citation' => 'nullable|string',
            'a_la_une' => 'nullable|boolean',
            'en_vedette' => 'nullable|boolean',
            'auteur_id' => 'required|exists:auteurs,id',
            'categorie_id' => 'required|exists:categories,id',
            'fichier_pdf' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,odt,odp|max:40240',
        ]);

        // Forcer les booléens
        $validated['a_la_une'] = $request->has('a_la_une') ? 1 : 0;
        $validated['en_vedette'] = $request->has('en_vedette') ? 1 : 0;

        // Gérer le fichier PDF/Word/PowerPoint
        if ($request->hasFile('fichier_pdf')) {
            // Supprimer l'ancien fichier s'il existe
            if ($publication->fichier_pdf && Storage::disk('public')->exists($publication->fichier_pdf)) {
                Storage::disk('public')->delete($publication->fichier_pdf);
            }

            // Enregistrer le nouveau fichier
            $filename = preg_replace('/\s+/', '_', $validated['titre']) . '.' . $request->file('fichier_pdf')->getClientOriginalExtension();
            $path = $request->file('fichier_pdf')->storeAs('assets', $filename, 'public');
            $validated['fichier_pdf'] = $path;
        }

        // Mise à jour de la publication
        $publication->update($validated);

        return redirect()->route('admin.publication.index')
            ->with('alert', '<span class="alert alert-success">Publication mise à jour avec succès.</span>');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
            ->withErrors($e->validator)
            ->withInput()
            ->with('alert', '<span class="alert alert-danger">Erreur de validation. Veuillez vérifier les champs.</span>');
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('alert', '<span class="alert alert-danger">Une erreur est survenue lors de la mise à jour : ' . e($e->getMessage()) . '</span>')
            ->withInput();
    }
}



    




    
public function updateFeatures(Request $request, Publication $publication)
{
    $validated = $request->validate([
        'en_vedette' => 'required|boolean',
        'a_la_une' => 'required|boolean',
    ]);

    $publication->update([
        'en_vedette' => $validated['en_vedette'],
        'a_la_une' => $validated['a_la_une'],
    ]);

    return redirect()->route('admin.publication.index')
        ->with('alert', '<span class="alert alert-success">Champs mis à jour avec succès.</span>');
}



    public function destroy(Publication $publication)
{
    try {
        // Supprimer le fichier PDF associé s’il existe
        if ($publication->fichier_pdf && Storage::disk('public')->exists($publication->fichier_pdf)) {
            Storage::disk('public')->delete($publication->fichier_pdf);
        }

        // Supprimer l'enregistrement
        $publication->delete();

        return redirect()->route('admin.publication.index')
            ->with('alert', '<span class="alert alert-success">Publication supprimée avec succès.</span>');
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('alert', '<span class="alert alert-danger">Erreur lors de la suppression : ' . e($e->getMessage()) . '</span>');
    }
}


public function show( $id)
    {
         $publication = Publication::with(['auteur', 'categorie'])->findOrFail($id);
        $fichierPath = storage_path('app/public/' . $publication->fichier_pdf);
        $extension = strtolower(pathinfo($fichierPath, PATHINFO_EXTENSION));
        $contenuHtml = null;

    if (in_array($extension, ['doc', 'docx'])) {
        $contenuHtml = $this->convertirDocxEnHtml($fichierPath);
    }
    return view('admin.publication.show', compact('publication', 'contenuHtml', 'extension'));
        // return view('admin.publication.show', compact('item'));
    }

     public function convertirDocxEnHtml($fileUrl)
{
    if (!file_exists($fileUrl)) {
        return '<p>Fichier introuvable.</p>';
    }
    // Vérification du type mime réel
    $expectedMime = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    if (mime_content_type($fileUrl) !== $expectedMime) {
        return '<p>Le fichier fourni n’est pas un fichier .docx valide (type mime incorrect).</p>';
    }

    try {
        $phpWord = IOFactory::load($fileUrl);
        $writer = IOFactory::createWriter($phpWord, 'HTML');

        // Capture la sortie HTML générée
        ob_start();
        $writer->save('php://output');
        $contenuHtml = ob_get_clean();

        return $contenuHtml;
    } catch (\Exception $e) {
        return '<p>Erreur lors de la lecture du fichier Word : ' . $e->getMessage() . '</p>';
    }
}
   
}