<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\JobApplicationRequest;
use App\Http\Requests\ContactRequest;
use App\Models\Publication;
use App\Models\Auteur;
use App\Models\Categorie;
use App\Models\Actualite;
use App\Models\Media;
use App\Models\Service;
use App\Models\Rapport;
use App\Models\Contact;
use App\Models\Newsletter;
use App\Models\NewsletterPreference;
use App\Models\Evenement;
use App\Models\Projet;
use App\Models\JobOffer;
use App\Models\JobApplication;
use App\Models\Partenaire;
use App\Models\ChercheurAffilie;
use App\Models\{Project, OurService, Event, User};
use App\Mail\{ContactMessage, ContactMessageWithCopy};
use App\Models\EmailSetting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{Log, Storage, DB};
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\LengthAwarePaginator;

class SiteController extends Controller
{
public function index(Request $request)
{
    $query = Publication::published()->with('auteur', 'categorie');

    if ($request->filled('auteur')) {
        $query->where('auteur_id', $request->auteur);
    }

    if ($request->filled('categorie')) {
        $query->where('categorie_id', $request->categorie);
    }

    $publications = $query->latest()->take(6)->get();
    
    // Récupérer également les rapports récents publiés
    $rapports = Rapport::published()->latest()->take(4)->get();
    
    // Combiner publications et rapports pour l'affichage
    $documentsRecents = $publications->merge($rapports)->sortByDesc('created_at')->take(8);

    $auteurs = Auteur::all();
    $categories = Categorie::all();
    // $publications= $this->publications($request);
     $actualites = Actualite::published()
                             ->where(function($query) {
                                 $query->where('en_vedette', true)
                                       ->orWhere('a_la_une', true);
                             })
                             ->latest()
                             ->take(6)
                             ->get();
     $services = Service::published()->get();
     
     // Récupérer les événements pour la sidebar (total de 5)
     // Récupération des événements en vedette et publiés
     $evenementsEnVedette = Evenement::where('en_vedette', true)
                                     ->where(function($query) {
                                         $query->where('is_published', true)
                                               ->orWhereNull('is_published'); // Compatibilité pour les anciens événements
                                     })
                                     ->orderBy('date_evenement', 'asc')
                                     ->take(5)
                                     ->get();
     
     $nombreEvenementsEnVedette = $evenementsEnVedette->count();
     $evenementsAutres = collect();

     // Si moins de 5 événements en vedette, compléter avec d'autres événements publiés
     if ($nombreEvenementsEnVedette < 5) {
         $nombreEvenementsAutres = 5 - $nombreEvenementsEnVedette;
         
         // Récupérer d'abord les événements à venir non en vedette mais publiés
         $evenementsAVenir = Evenement::where('en_vedette', false)
                                     ->where(function($query) {
                                         $query->where('is_published', true)
                                               ->orWhereNull('is_published'); // Compatibilité pour les anciens événements
                                     })
                                     ->aVenir()
                                     ->orderBy('date_evenement', 'asc')
                                     ->take($nombreEvenementsAutres)
                                     ->get();
                                     
         $nombreRestant = $nombreEvenementsAutres - $evenementsAVenir->count();
         
         // Si toujours pas assez, compléter avec des événements passés publiés
         if ($nombreRestant > 0) {
             $evenementsPasses = Evenement::where('en_vedette', false)
                                         ->where(function($query) {
                                             $query->where('is_published', true)
                                                   ->orWhereNull('is_published'); // Compatibilité pour les anciens événements
                                         })
                                         ->passe()
                                         ->orderBy('date_evenement', 'desc')
                                         ->take($nombreRestant)
                                         ->get();
             $evenementsAutres = $evenementsAVenir->merge($evenementsPasses);
         } else {
             $evenementsAutres = $evenementsAVenir;
         }
     }

     $evenements = $evenementsEnVedette->merge($evenementsAutres);     // Statistiques des projets (uniquement les projets publiés)
     $statsProjects = [
         'total_projets' => Projet::published()->count(),
         'projets_en_cours' => Projet::published()->where('etat', 'en cours')->count(),
         'projets_termines' => Projet::published()->where('etat', 'terminé')->count(),
         'total_beneficiaires' => Projet::published()->sum('beneficiaires_total') ?: 0,
         'beneficiaires_hommes' => Projet::published()->sum('beneficiaires_hommes') ?: 0,
         'beneficiaires_femmes' => Projet::published()->sum('beneficiaires_femmes') ?: 0,
         'zones_intervention' => Projet::published()->whereNotNull('service_id')->distinct('service_id')->count(),
         'projets_par_secteur' => Service::published()->withCount(['projets' => function($query) {
             $query->published();
         }])->get(),
         'beneficiaires_par_secteur' => Service::published()->with(['projets' => function($query) {
             $query->published();
         }])->get()->map(function($service) {
             return [
                 'nom' => $service->nom,
                 'total_beneficiaires' => $service->projets->sum('beneficiaires_total'),
                 'beneficiaires_hommes' => $service->projets->sum('beneficiaires_hommes'),
                 'beneficiaires_femmes' => $service->projets->sum('beneficiaires_femmes'),
             ];
         })
     ];

     // Récupérer les partenaires avec leurs logos pour l'affichage
     $partenaires = \App\Models\Partenaire::whereNotNull('logo')
                                         ->publics()
                                         ->actifs()
                                         ->ordonnes()
                                         ->get();

    return view('index', compact('documentsRecents', 'auteurs', 'actualites', 'categories', 'services', 'evenements', 'statsProjects', 'partenaires', 'request'));
}


public function actualites(Request $request)
{
    $query = Actualite::published();

    if ($request->filled('categorie')) {
        $query->where('categorie_id', $request->categorie);
    }

    $actualites = $query->latest()->paginate(20)->appends($request->query());

    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Actualités', 'url' => null]
    ];

    return view('actualites', compact('actualites', 'breadcrumbs'));
}

public function actualiteShow($slug)
{
    $actualite = Actualite::published()
                    ->with('categorie')
                    ->where('slug', $slug)
                    ->firstOrFail();
    $recentActualites = Actualite::published()->latest()->where('id', '!=', $actualite->id)->take(10)->get();

    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Actualités', 'url' => route('site.actualites')],
        ['title' => $actualite->titre, 'url' => null]
    ];

    return view('showactualite', compact('actualite','recentActualites', 'breadcrumbs'));
}

public function actualiteShowById($id)
{
    $actualite = Actualite::with('categorie')->findOrFail($id);
    
    // Rediriger vers l'URL avec slug pour le SEO
    return redirect()->route('site.actualite', ['slug' => $actualite->slug]);
}

public function evenementShow($slug)
{
    $evenement = Evenement::where('slug', $slug)
                          ->where(function($query) {
                              $query->where('is_published', true)
                                    ->orWhereNull('is_published'); // Compatibilité pour les anciens événements
                          })
                          ->firstOrFail();
    
    // Récupérer d'autres événements récents publiés pour suggestions
    $autresEvenements = Evenement::where('id', '!=', $evenement->id)
                                 ->where(function($query) {
                                     $query->where('is_published', true)
                                           ->orWhereNull('is_published'); // Compatibilité pour les anciens événements
                                 })
                                 ->orderBy('date_evenement', 'desc')
                                 ->take(4)
                                 ->get();
    
    return view('show-evenement', compact('evenement', 'autresEvenements'));
}

public function services(Request $request)
{
    // Récupérer tous les services publiés avec leurs statistiques
    $services = \App\Models\Service::published()
                    ->with(['projets' => function($query) {
                        $query->published();
                    }, 'actualites' => function($query) {
                        $query->published();
                    }])
                    ->get();

    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Domaines d\'intervention', 'url' => null]
    ];

    return view('services', compact('services', 'breadcrumbs'));
}

public function serviceshow($slug)
{
    // Charger le service publié par son slug
    $service = \App\Models\Service::published()->where('slug', $slug)->first();

    // Vérification : si aucun service trouvé, on renvoie quand même un objet vide
    if (!$service) {
        $service = new \App\Models\Service(); // un objet vide
        return view('showservice', compact('service'));
    }

    // Charger les projets publiés avec 4 médias aléatoires pour chaque projet ET les actualités publiées liées
    $service->load([
        'projets' => function($query) {
            $query->published()->with(['medias' => function($mediaQuery) {
                $mediaQuery->inRandomOrder()->limit(4);
            }]);
        },
        'actualites' => function($query) {
            $query->published()->latest()->limit(10); // Les 10 actualités les plus récentes publiées
        }
    ]);

    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Domaines d\'intervention', 'url' => route('site.services')],
        ['title' => $service->nom, 'url' => null]
    ];

    return view('showservice', compact('service', 'breadcrumbs'));
}

public function projetShow($slug)
{
    $projet = \App\Models\Projet::published()->where('slug', $slug)->with(['service', 'medias'])->firstOrFail();
    
    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Projets', 'url' => route('site.projets')],
        ['title' => $projet->nom, 'url' => null]
    ];
    
    return view('showprojet', compact('projet', 'breadcrumbs'));
}

public function projets(Request $request)
{
    $query = \App\Models\Projet::published()->with(['service', 'medias']);

    // Filtrer par service si spécifié
    if ($request->filled('service')) {
        $query->where('service_id', $request->service);
    }

    // Filtrer par statut si spécifié
    if ($request->filled('etat')) {
        $query->where('etat', $request->etat);
    }

    $projets = $query->latest()->paginate(12)->appends($request->query());

    // Charger les services publiés pour le filtrage
    $services = \App\Models\Service::published()->get();

    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Projets', 'url' => null]
    ];

    return view('projets', compact('projets', 'services', 'request', 'breadcrumbs'));
}

public function serviceProjects($slug)
{
    $service = \App\Models\Service::where('slug', $slug)->firstOrFail();
    
    $projets = $service->projets()->with('medias')->latest()->paginate(12);
    $services = \App\Models\Service::all();
    
    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Domaines d\'intervention', 'url' => route('site.services')],
        ['title' => $service->nom, 'url' => route('site.service.show', $service->slug)],
        ['title' => 'Projets', 'url' => null]
    ];
    
    return view('projets', compact('projets', 'services', 'service', 'breadcrumbs'));
}

public function serviceActualites($slug)
{
    $service = \App\Models\Service::where('slug', $slug)->firstOrFail();
    
    $actualites = $service->actualites()->latest()->paginate(20);
    
    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Domaines d\'intervention', 'url' => route('site.services')],
        ['title' => $service->nom, 'url' => route('site.service.show', $service->slug)],
        ['title' => 'Actualités', 'url' => null]
    ];
    
    return view('actualites', compact('actualites', 'service', 'breadcrumbs'));
}

// public function publicationShow($id)
// {
//     $publication = Publication::with(['auteur', 'categorie'])->findOrFail($id);
//     $fichierPath = storage_path('app/public/' . $publication->fichier_pdf);
//     $extension = strtolower(pathinfo($fichierPath, PATHINFO_EXTENSION));
//     $contenuHtml = null;

//     if (in_array($extension, ['doc', 'docx'])) {
//         $contenuHtml = $this->convertirDocxEnHtml($fichierPath);
//     }
    
//     return view('publication.show', compact('publication', 'contenuHtml', 'extension'));
// }


public function convertirImage()
{
    $publications = \App\Models\Publication::all();

    return view('convert', compact('publications'));
}

public function convertirImageUnique(\App\Models\Publication $publication)
{
    try {
        if (!extension_loaded('imagick')) {
            throw new \Exception("L'extension Imagick n'est pas chargée. Vérifiez la configuration du serveur.");
        }

        $pdfPath = storage_path('app/public/' . $publication->fichier_pdf);
        if (!file_exists($pdfPath)) {
            throw new \Exception("Le fichier PDF n'existe pas à l'emplacement prévu.");
        }

        $thumbName = pathinfo($publication->fichier_pdf, PATHINFO_FILENAME) . '.jpg';
        $thumbPath = storage_path('app/public/thumbnails/' . $thumbName);

        if (!file_exists($thumbPath)) {
            // S'assure que le répertoire existe
            \Storage::disk('public')->makeDirectory('thumbnails');

            // Conversion
            $image = new \Imagick();
            $image->setResolution(150, 150);
            $image->readImage($pdfPath . '[0]');
            $image->setImageFormat('jpg');
            $image->writeImage($thumbPath);
            $image->clear();
            $image->destroy();
        }

        // Succès
        return back()->with('alert', "<span class='alert alert-success'>Image générée avec succès pour : {$publication->titre}</span>");

    } catch (\Exception $e) {
        // Log en plus pour dev / production
        \Log::error("Erreur génération thumbnail PDF (ID {$publication->id}): " . $e->getMessage());

        return back()->with('alert', "<span class='alert alert-danger'>Erreur lors de la génération : {$e->getMessage()}</span>");
    }
}





// public function CovnertImage(){
//     $publication=Publication::All();
//     return view('convert', compact('publications'));
// }

public function publications(Request $request)
{
    $query = Publication::published()->with('auteur', 'categorie');

    if ($request->filled('auteur')) {
        $query->where('auteur_id', $request->auteur);
    }

    if ($request->filled('categorie')) {
        $query->where('categorie_id', $request->categorie);
    }

    $publications = $query->latest()->paginate(20)->appends($request->query());

    // charger les catégories
    $categories = Categorie::all();

    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Publications', 'url' => null]
    ];

    return view('publications', compact('publications', 'categories', 'request', 'breadcrumbs'));
}




public function search(Request $request)
{
    $start = microtime(true);
    $query = $request->input('q', $request->input('search')); // Support des deux paramètres

    // Publications
    $publications = Publication::with('categorie', 'auteur')
        ->where('titre', 'like', "%{$query}%")
        ->orWhere('resume', 'like', "%{$query}%")
        ->get()
        ->map(function($item) {
            $item->type_global = 'Publication';
            $item->date_global = $item->created_at;
            return $item;
        });

    // Actualités (titre, resume, texte)
    $actualites = Actualite::where('titre', 'like', "%{$query}%")
        ->orWhere('resume', 'like', "%{$query}%")
        ->orWhere('texte', 'like', "%{$query}%")
        ->get()
        ->map(function($item) {
            $item->type_global = 'Actualité';
            $item->date_global = $item->created_at;
            return $item;
        });

    // Rapports
    $rapports = Rapport::where('titre', 'like', "%{$query}%")
        ->orWhere('description', 'like', "%{$query}%")
        ->get()
        ->map(function($item) {
            $item->type_global = 'Rapport';
            $item->date_global = $item->created_at;
            return $item;
        });

    // Projets
    $projets = \App\Models\Projet::with('service')
        ->where('nom', 'like', "%{$query}%")
        ->orWhere('description', 'like', "%{$query}%")
        ->orWhere('resume', 'like', "%{$query}%")
        ->get()
        ->map(function($item) {
            $item->type_global = 'Projet';
            $item->date_global = $item->created_at;
            $item->titre = $item->nom; // Pour la compatibilité avec la vue
            return $item;
        });

    // Fusionner tout et trier par date décroissante
    $results = $publications->merge($actualites)->merge($rapports)->merge($projets)
        ->sortByDesc('date_global')->values();

    // Pagination manuelle
    $page = $request->get('page', 1);
    $perPage = 12;
    $paginated = new LengthAwarePaginator(
        $results->forPage($page, $perPage),
        $results->count(),
        $perPage,
        $page,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    // Temps d'exécution
    $elapsed = round(microtime(true) - $start, 2);

    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Recherche', 'url' => null]
    ];

    return view('search_results', [
        'results' => $paginated,
        'query' => $query,
        'elapsed' => $elapsed,
        'totalResults' => $results->count(),
        'breadcrumbs' => $breadcrumbs
    ]);
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
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($fileUrl);
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');

        // Capture la sortie HTML générée
        ob_start();
        $writer->save('php://output');
        $contenuHtml = ob_get_clean();

        return $contenuHtml;
    } catch (\Exception $e) {
        return '<p>Erreur lors de la lecture du fichier Word : ' . $e->getMessage() . '</p>';
    }
}

public function galerie(Request $request)
{
    // $medias = Media::inRandomOrder()->get(); // Aléatoire
    $query = Media::query();

    if ($request->has('type') && in_array($request->type, ['image', 'video'])) {
        $query->where('type', $request->type);
    }

    $medias = $query->orderBy('created_at', 'desc')->paginate(20);

    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Galerie', 'url' => null]
    ];

    // return view('admin.media.index', compact('medias'));
    return view('galerie', compact('medias', 'breadcrumbs'));
}



public function publicationShow($slug)
{
    $publication = Publication::published()
        ->with(['auteurs', 'categorie'])
        ->where('slug', $slug)
        ->firstOrFail();

    $fichierPath = storage_path('app/public/' . $publication->fichier_pdf);
    $extension = strtolower(pathinfo($fichierPath, PATHINFO_EXTENSION));
    $fileUrl = asset('storage/' . $publication->fichier_pdf);

    $autresPublications = collect();
    $auteur = null;
    $contenuHtml = null;

    if (in_array($extension, ['doc', 'docx'])) {
        $contenuHtml = $this->convertirDocxEnHtml($fichierPath);
    }

    // Gérer les relations many-to-many avec auteurs
    if ($publication->auteurs->count() > 0) {
        // Prendre le premier auteur pour les publications similaires
        $premierAuteur = $publication->auteurs->first();
        
        $autresPublications = $premierAuteur->publications()
            ->published()
            ->where('publications.id', '!=', $publication->id)
            ->orderBy('publications.created_at', 'desc')
            ->take(5)
            ->get();
    }

    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Publications', 'url' => route('site.publications')],
        ['title' => $publication->titre, 'url' => null]
    ];

    return view('showpublication', compact(
        'publication', 'contenuHtml', 'auteur',
        'extension', 'fileUrl', 'autresPublications', 'breadcrumbs'
    ));
}


//      public function convertirDocxEnHtml($fileUrl)
// {
//     if (!file_exists($fileUrl)) {
//         return '<p>Fichier introuvable.</p>';
//     }
//     // Vérification du type mime réel
//     $expectedMime = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
//     if (mime_content_type($fileUrl) !== $expectedMime) {
//         return '<p>Le fichier fourni n’est pas un fichier .docx valide (type mime incorrect).</p>';
//     }

//     try {
//         $phpWord = IOFactory::load($fileUrl);
//         $writer = IOFactory::createWriter($phpWord, 'HTML');

//         // Capture la sortie HTML générée
//         ob_start();
//         $writer->save('php://output');
//         $contenuHtml = ob_get_clean();

//         return $contenuHtml;
//     } catch (\Exception $e) {
//         return '<p>Erreur lors de la lecture du fichier Word : ' . $e->getMessage() . '</p>';
//     }
// }

public function contact()
{
    // Breadcrumbs
    $breadcrumbs = [
        ['title' => 'Contact', 'url' => null]
    ];
    
    return view('contact', compact('breadcrumbs'));
}

public function storeContact(ContactRequest $request)
{
    try {
        $contact = null;
        $emailResult = null;
        
        // Utiliser une transaction pour s'assurer que tout se passe bien
        DB::transaction(function () use ($request, &$contact, &$emailResult) {
            // 1. Enregistrer le message de contact
            $contact = Contact::create([
                'nom' => $request->nom,
                'email' => $request->email,
                'sujet' => $request->sujet,
                'message' => $request->message,
                'statut' => 'nouveau'
            ]);

            // 2. Ajouter automatiquement l'email à la newsletter (si pas déjà présent)
            $newsletter = Newsletter::firstOrCreate(['email' => $request->email]);

            // 3. Envoyer les emails avec le système de copie
            $emailResult = ContactMessageWithCopy::sendToConfiguredEmails($contact);
        });

        
        // Vérifier le résultat de l'envoi d'email
        if ($emailResult && $emailResult['success']) {
            $successMessage = 'Votre message a été envoyé avec succès ! ' .
                            'Nous vous répondrons dans les plus brefs délais. ' .
                            'Un email de confirmation vous a été envoyé. ' .
                            'Vous avez également été ajouté à notre liste de diffusion.';
                            
            // Log du succès pour le suivi
            Log::info('Message de contact envoyé avec succès', [
                'contact_id' => $contact->id,
                'total_emails_sent' => $emailResult['total_sent']
            ]);
        } else {
            $successMessage = 'Votre message a été enregistré avec succès ! ' .
                            'Cependant, il y a eu un problème avec l\'envoi automatique des emails. ' .
                            'Nous vous contacterons directement. ' .
                            'Vous avez été ajouté à notre liste de diffusion.';
                            
            // Log de l'erreur pour investigation
            Log::warning('Échec partiel envoi email contact', [
                'contact_id' => $contact->id,
                'email_error' => $emailResult['error'] ?? 'Erreur inconnue'
            ]);
        }

        return redirect()->route('site.contact')->with('success', $successMessage);

    } catch (\Exception $e) {
        Log::error('Erreur complète lors de la soumission du contact', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request_data' => $request->except(['_token'])
        ]);
        
        return redirect()->route('site.contact')
                         ->with('error', 'Une erreur s\'est produite lors de l\'envoi de votre message. Veuillez réessayer ou nous contacter directement.')
                         ->withInput();
    }
}

/**
 * Gérer l'inscription newsletter depuis le footer
 */
public function subscribeNewsletter(Request $request)
{
    try {
        \Log::info('Newsletter subscription attempt', [
            'email' => $request->email,
            'nom' => $request->nom,
            'preferences' => $request->preferences,
            'all_data' => $request->all()
        ]);

        // Validation flexible
        $request->validate([
            'email' => 'required|email|max:255',
            'nom' => 'nullable|string|max:255', // Le nom est optionnel
            'preferences' => 'nullable|array', // Les préférences sont optionnelles
            'preferences.*' => 'in:actualites,publications,rapports,evenements'
        ]);

        // Préparer les préférences par défaut si aucune n'est fournie
        $preferences = ['actualites' => true, 'publications' => true]; // Valeurs par défaut
        
        if ($request->has('preferences') && is_array($request->preferences)) {
            // Si des préférences sont envoyées, les utiliser
            $preferences = [];
            foreach(['actualites', 'publications', 'rapports', 'evenements'] as $type) {
                $preferences[$type] = in_array($type, $request->preferences);
            }
            
            // S'assurer qu'au moins une préférence est activée
            if (!array_filter($preferences)) {
                $preferences = ['actualites' => true, 'publications' => true];
            }
        }

        \Log::info('Prepared preferences', ['preferences' => $preferences]);

        // Vérifier si l'email existe déjà
        $existing = \DB::table('newsletters')->where('email', $request->email)->first();
        
        if ($existing) {
            if ($existing->actif) {
                \Log::info('Newsletter subscription attempt for existing active user', ['email' => $request->email]);
                return back()->with('info', 'Cette adresse email est déjà inscrite à notre newsletter. Vous recevez déjà nos actualités !');
            } else {
                // Réactiver l'abonnement
                \DB::table('newsletters')
                    ->where('email', $request->email)
                    ->update([
                        'actif' => 1,
                        'nom' => $request->nom ?: $existing->nom ?: 'Abonné',
                        'preferences' => json_encode($preferences),
                        'updated_at' => now()
                    ]);
                
                \Log::info('Newsletter subscription reactivated', ['email' => $request->email]);
                return back()->with('success', 'Votre abonnement à la newsletter a été réactivé avec succès !');
            }
        }

        // Nouvelle inscription
        $inserted = \DB::table('newsletters')->insert([
            'email' => $request->email,
            'nom' => $request->nom ?: 'Abonné', // Nom par défaut si vide
            'token' => bin2hex(random_bytes(32)),
            'actif' => 1,
            'preferences' => json_encode($preferences),
            'emails_sent_count' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if ($inserted) {
            \Log::info('Newsletter subscription successful', [
                'email' => $request->email,
                'preferences' => $preferences
            ]);
            
            // Envoyer l'email de bienvenue pour les nouvelles inscriptions
            try {
                // Récupérer l'enregistrement nouvellement créé pour avoir l'ID et le token
                $newsletter = \DB::table('newsletters')->where('email', $request->email)->first();
                
                if ($newsletter) {
                    // Créer une instance Newsletter pour le service
                    $newsletterModel = new \App\Models\Newsletter();
                    $newsletterModel->fill([
                        'id' => $newsletter->id,
                        'email' => $newsletter->email,
                        'nom' => $newsletter->nom,
                        'token' => $newsletter->token,
                        'actif' => $newsletter->actif,
                        'preferences' => json_decode($newsletter->preferences, true)
                    ]);
                    $newsletterModel->exists = true; // Indiquer que c'est un modèle existant
                    
                    $newsletterService = app(\App\Services\NewsletterService::class);
                    $emailSent = $newsletterService->sendWelcomeEmail($newsletterModel);
                    
                    if ($emailSent) {
                        \Log::info('Newsletter welcome email sent successfully', ['email' => $request->email]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Newsletter welcome email failed', [
                    'email' => $request->email,
                    'error' => $e->getMessage()
                ]);
                // Ne pas faire échouer l'inscription si l'email échoue
            }
            
            return back()->with('success', 'Inscription réussie ! Merci de vous être abonné à notre newsletter.');
        } else {
            \Log::error('Newsletter subscription insert failed');
            return back()->with('error', 'Erreur lors de l\'enregistrement.');
        }

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::warning('Newsletter subscription validation failed', [
            'email' => $request->email ?? 'unknown',
            'errors' => $e->errors()
        ]);
        
        return back()->with('error', 'Veuillez vérifier l\'adresse email saisie.');
        
    } catch (\Exception $e) {
        \Log::error('Newsletter subscription error', [
            'email' => $request->email ?? 'unknown',
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()->with('error', 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer.');
    }
}

public function workWithUs()
{
    // Données d'impact réelles depuis la base de données
    $impactStats = [
        'projets_actifs' => Projet::where('etat', 'en_cours')->count(),
        'projets_termines' => Projet::where('etat', 'termine')->count(),
        'publications' => Publication::count(),
        'rapports' => Rapport::count(),
        'services' => Service::count(),
        'actualites' => Actualite::count(),
        'beneficiaires' => Projet::sum('beneficiaires_total'), // Somme des bénéficiaires de tous les projets
        'partenaires' => Partenaire::actifs()->count(), // Nombre de partenaires actifs
    ];

    // Récupérer les offres d'emploi actives
    $jobOffers = JobOffer::active()
        ->orderBy('is_featured', 'desc')
        ->orderBy('created_at', 'desc')
        ->get();

    // Statistiques des offres d'emploi
    $jobStats = [
        'total_offers' => JobOffer::count(),
        'active_offers' => JobOffer::active()->count(),
        'expired_offers' => JobOffer::expired()->count(),
        'internal_offers' => JobOffer::bySource('interne')->active()->count(),
        'partner_offers' => JobOffer::bySource('partenaire')->active()->count(),
        'total_applications' => JobApplication::count(),
        'pending_applications' => JobApplication::pending()->count(),
    ];

    $breadcrumbData = [
        'currentPage' => 'Travailler avec nous',
        'breadcrumbs' => [
            ['name' => 'Accueil', 'url' => route('site.home')],
            ['name' => 'Travailler avec nous', 'url' => null]
        ]
    ];

    return view('work-with-us', compact('impactStats', 'jobOffers', 'jobStats') + $breadcrumbData);
}

/**
 * Afficher la page des partenariats
 */
public function partenariats()
{
    $breadcrumbData = [
        'currentPage' => 'Partenariats & Collaborations',
        'breadcrumbs' => [
            ['name' => 'Accueil', 'url' => route('site.home')],
            ['name' => 'Partenariats', 'url' => null]
        ]
    ];

    // Récupérer les partenaires publics
    $partenaires = Partenaire::publics()->actifs()->ordonnes()->get();
    
    // Récupérer les chercheurs affiliés
    $chercheurs = ChercheurAffilie::actifs()->publics()->orderBy('ordre_affichage')->get();
    
    // Statistiques d'impact basées sur les données réelles
    $partnershipStats = [
        'universites_partenaires' => Partenaire::parType('universite')->actifs()->count(),
        'organisations_collaboratrices' => Partenaire::parType('organisation_internationale')->actifs()->count(),
        'chercheurs_affilies' => ChercheurAffilie::actifs()->count(),
        'projets_collaboratifs' => Projet::count() // Tous les projets sont considérés comme collaboratifs dans ce contexte
    ];

    return view('partenariats', compact('partnershipStats', 'partenaires', 'chercheurs') + $breadcrumbData);
}

public function showJobApplication(JobOffer $job)
{
    // Vérifier si l'offre est encore active
    if ($job->is_expired || $job->status !== 'active') {
        return redirect()->route('site.work-with-us')->with('error', 'Cette offre d\'emploi n\'est plus disponible.');
    }

    // Incrémenter le nombre de vues
    $job->incrementViews();

    $breadcrumbData = [
        'currentPage' => 'Candidature - ' . $job->title,
        'breadcrumbs' => [
            ['name' => 'Accueil', 'url' => route('site.home')],
            ['name' => 'Travailler avec nous', 'url' => route('site.work-with-us')],
            ['name' => 'Candidature', 'url' => null]
        ]
    ];

    return view('job-application', compact('job') + $breadcrumbData);
}

public function submitJobApplication(JobApplicationRequest $request, JobOffer $job)
{
    // Vérifier si l'offre est encore active
    if ($job->is_expired || $job->status !== 'active') {
        return redirect()->route('site.work-with-us')->with('error', 'Cette offre d\'emploi n\'est plus disponible.');
    }

    try {
        // Upload des fichiers avec validation supplémentaire
        $cvPath = null;
        $portfolioPath = null;

        if ($request->hasFile('cv_file')) {
            $cvFile = $request->file('cv_file');
            // Générer un nom unique pour éviter les conflits
            $cvFileName = time() . '_cv_' . $job->id . '_' . Str::slug($request->last_name) . '.' . $cvFile->getClientOriginalExtension();
            $cvPath = $cvFile->storeAs('job-applications/cv', $cvFileName, 'public');
        }

        if ($request->hasFile('portfolio_file')) {
            $portfolioFile = $request->file('portfolio_file');
            $portfolioFileName = time() . '_portfolio_' . $job->id . '_' . Str::slug($request->last_name) . '.' . $portfolioFile->getClientOriginalExtension();
            $portfolioPath = $portfolioFile->storeAs('job-applications/portfolio', $portfolioFileName, 'public');
        }

        // Créer la candidature avec les données validées
        $application = JobApplication::create([
            'job_offer_id' => $job->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'nationality' => $request->nationality,
            'education' => $request->education,
            'experience' => $request->experience,
            'skills' => $request->skills,
            'motivation_letter' => $request->motivation_letter,
            'criteria_responses' => $request->criteria_responses,
            'cv_path' => $cvPath,
            'portfolio_path' => $portfolioPath,
        ]);

        // Incrémenter le compteur de candidatures
        $job->incrementApplications();

        // Optionnel : Envoyer un email de confirmation
        // Mail::to($request->email)->send(new JobApplicationConfirmation($application));

        return redirect()->route('site.work-with-us')
            ->with('success', 'Votre candidature a été soumise avec succès. Nous vous contacterons bientôt.');

    } catch (\Exception $e) {
        \Log::error('Erreur lors de la soumission de candidature: ' . $e->getMessage());
        return back()->with('error', 'Une erreur est survenue lors de la soumission de votre candidature. Veuillez réessayer.');
    }
}

/**
 * Télécharger le document d'appel d'offre de manière sécurisée
 */
public function downloadJobDocument(JobOffer $job)
{
    try {
        // Vérifier que l'offre a un document
        if (!$job->hasDocumentAppelOffre()) {
            abort(404, 'Document non trouvé');
        }

        // Vérifier que le fichier existe dans le storage
        if (!Storage::disk('public')->exists($job->document_appel_offre)) {
            abort(404, 'Fichier non trouvé');
        }

        // Déterminer le nom du fichier à télécharger
        $fileName = $job->document_appel_offre_nom ?: 'appel_offre_' . $job->id . '.pdf';
        
        // Incrémenter les vues (optionnel, car c'est un téléchargement)
        $job->incrementViews();

        // Retourner le fichier en téléchargement
        return Storage::disk('public')->download($job->document_appel_offre, $fileName);

    } catch (\Exception $e) {
        Log::error('Erreur lors du téléchargement du document d\'appel d\'offre: ' . $e->getMessage());
        abort(500, 'Erreur lors du téléchargement');
    }
}

public function evenements()
{
    $evenements = Evenement::where(function($query) {
                                $query->where('is_published', true)
                                      ->orWhereNull('is_published'); // Compatibilité pour les anciens événements
                            })
                           ->orderBy('date_evenement', 'desc')
                           ->paginate(12);

    return view('site.evenements', compact('evenements'));
}
   
}