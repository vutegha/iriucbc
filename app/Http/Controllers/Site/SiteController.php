<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use App\Models\Publication;
use App\Models\Auteur;
use App\Models\Categorie;
use App\Models\Actualite;
use App\Models\Media;
use Illuminate\Support\Str;
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


   use App\Models\Media;

public function galerie(Request $request)
{
    // $medias = Media::inRandomOrder()->get(); // Aléatoire
    $query = Media::query();

    if ($request->has('type') && in_array($request->type, ['image', 'video'])) {
        $query->where('type', $request->type);
    }

    $medias = $query->latest()->get(); // ou paginate()

    // return view('admin.media.index', compact('medias'));
    return view('galerie', compact('medias'));
}
public function publication(Request $request)
{
    $query = Media::query();

    if ($request->has('type') && in_array($request->type, ['image', 'video'])) {
        $query->where('type', $request->type);
    }

    $medias = $query->latest()->get(); // ou paginate()

    return view('admin.media.index', compact('medias'));
}




   








    




    



public function publicationShow( $id)
    {
         $publication = Publication::with(['auteur', 'categorie'])->findOrFail($id);
        $fichierPath = storage_path('app/public/' . $publication->fichier_pdf);
        $extension = strtolower(pathinfo($fichierPath, PATHINFO_EXTENSION));
        $contenuHtml = null;

    if (in_array($extension, ['doc', 'docx'])) {
        $contenuHtml = $this->convertirDocxEnHtml($fichierPath);
    }
    return view('publication.show', compact('publication', 'contenuHtml', 'extension'));
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