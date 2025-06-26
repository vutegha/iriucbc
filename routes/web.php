<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ActualiteController;
use App\Http\Controllers\Admin\PublicationController;
use App\Http\Controllers\Admin\AuteurController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\CategorieController;
use App\Http\Controllers\Admin\RapportController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ProjetController;
use App\Http\Controllers\Site\SiteController;

// use Illuminate\Support\Facades\Route;


// route frontend 
Route::get('/', function () {
    return view('home');
});

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/galerie', [SiteController::class, 'services'])->name('galerie');
Route::get('/publications', [SiteController::class, 'publications'])->name('publications');
Route::get('/publications/{slug}', [SiteController::class, 'showPublication'])->name('publications.show');
Route::get('/actualites', [SiteController::class, 'actualites'])->name('site.actualites');
Route::get('/contact', [SiteController::class, 'contact'])->name('site.contact');


// route backend


// Groupe d'administration (facultatif à adapter selon tes middleware ou préfixes)
Route::prefix('admin')->name('admin.')->group(function () {
    


    // Actualite
    Route::get('/actualite', [ActualiteController::class, 'index'])->name('actualite.index');
    Route::get('/actualite/create', [ActualiteController::class, 'create'])->name('actualite.create');
    Route::post('/actualite', [ActualiteController::class, 'store'])->name('actualite.store');
    // Route::get('/actualite/{id}/edit', [ActualiteController::class, 'edit'])->name('actualite.edit');
    Route::get('admin/actualite/{actualite}/edit', [ActualiteController::class, 'edit'])->name('actualite.edit');

    // Route::put('/actualite/{id}', [ActualiteController::class, 'update'])->name('actualite.update');
    Route::put('admin/actualite/{actualite}', [ActualiteController::class, 'update'])->name('actualite.update');

    Route::delete('/actualite/{actualite}', [ActualiteController::class, 'destroy'])->name('actualite.destroy');
    // Route::put('/actualite/{id}/updatefeatures', [ActualiteController::class, 'updateFeatures'])->name('actualite.updatefeatures');
    Route::put('/actualite/{actualite}/updatefeatures', [ActualiteController::class, 'updateFeatures'])->name('actualite.updatefeatures');
    Route::get('/actualite/{id}/show', [ActualiteController::class, 'show'])->name('actualite.show');

    // publication
    Route::get('/publication', [PublicationController::class, 'index'])->name('publication.index');
    Route::get('/publication/create', [PublicationController::class, 'create'])->name('publication.create');
    Route::post('/publication', [PublicationController::class, 'store'])->name('publication.store');
    // Route::get('/publication/{id}/edit', [PublicationController::class, 'edit'])->name('publication.edit');
    Route::get('admin/publication/{publication}/edit', [PublicationController::class, 'edit'])->name('publication.edit');

    // Route::put('/publication/{id}', [PublicationController::class, 'update'])->name('publication.update');
    Route::put('admin/publication/{publication}', [PublicationController::class, 'update'])->name('publication.update');

    Route::delete('/publication/{publication}', [PublicationController::class, 'destroy'])->name('publication.destroy');
    // Route::put('/publication/{id}/updatefeatures', [PublicationController::class, 'updateFeatures'])->name('publication.updatefeatures');
    Route::put('/publication/{publication}/updatefeatures', [PublicationController::class, 'updateFeatures'])->name('publication.updatefeatures');
    Route::get('/publication/{id}/show', [PublicationController::class, 'show'])->name('publication.show');
    
    // Auteur
    Route::get('/auteur', [AuteurController::class, 'index'])->name('auteur.index');
    Route::get('/auteur/create', [AuteurController::class, 'create'])->name('auteur.create');
    Route::post('/auteur', [AuteurController::class, 'store'])->name('auteur.store');
    Route::get('/auteur/{auteur}/edit', [AuteurController::class, 'edit'])->name('auteur.edit');
    Route::put('/auteur/{auteur}', [AuteurController::class, 'update'])->name('auteur.update');
    Route::delete('/auteur/{auteur}', [AuteurController::class, 'destroy'])->name('auteur.destroy');
    Route::get('/auteur/{auteur}/show', [AuteurController::class, 'show'])->name('auteur.show');

    // Service
    Route::get('/service', [ServiceController::class, 'index'])->name('service.index');
    Route::get('/service/create', [ServiceController::class, 'create'])->name('service.create');
    Route::post('/service', [ServiceController::class, 'store'])->name('service.store');
    Route::get('/service/{service}/edit', [ServiceController::class, 'edit'])->name('service.edit');
    Route::put('/service/{service}', [ServiceController::class, 'update'])->name('service.update');
    Route::delete('/service/{service}', [ServiceController::class, 'destroy'])->name('service.destroy');

    // Newsletter
    Route::get('/newsletter', [NewsletterController::class, 'index'])->name('newsletter.index');
    Route::get('/newsletter/create', [NewsletterController::class, 'create'])->name('newsletter.create');
    Route::post('/newsletter', [NewsletterController::class, 'store'])->name('newsletter.store');
    Route::get('/newsletter/{newsletter}/edit', [NewsletterController::class, 'edit'])->name('newsletter.edit');
    Route::put('/newsletter/{newsletter}', [NewsletterController::class, 'update'])->name('newsletter.update');
    Route::delete('/newsletter/{newsletter}', [NewsletterController::class, 'destroy'])->name('newsletter.destroy');

    // Categorie
    Route::get('/categorie', [CategorieController::class, 'index'])->name('categorie.index');
    Route::get('/categorie/create', [CategorieController::class, 'create'])->name('categorie.create');
    Route::post('/categorie', [CategorieController::class, 'store'])->name('categorie.store');
    Route::get('/categorie/{categorie}/edit', [CategorieController::class, 'edit'])->name('categorie.edit');
    Route::put('/categorie/{categorie}', [CategorieController::class, 'update'])->name('categorie.update');
    Route::delete('/categorie/{categorie}', [CategorieController::class, 'destroy'])->name('categorie.destroy');

    // Rapport
    Route::get('/rapports', [RapportController::class, 'index'])->name('rapports.index');
    Route::get('/rapports/create', [RapportController::class, 'create'])->name('rapports.create');
    Route::post('/rapports', [RapportController::class, 'store'])->name('rapports.store');
    Route::get('/rapports/{rapport}/edit', [RapportController::class, 'edit'])->name('rapports.edit');
    Route::put('/rapports/{rapport}', [RapportController::class, 'update'])->name('rapports.update');
    Route::delete('/rapports/{rapport}', [RapportController::class, 'destroy'])->name('rapports.destroy');

    // Media
    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::get('/media/create', [MediaController::class, 'create'])->name('media.create');
    Route::post('/media', [MediaController::class, 'store'])->name('media.store');
    Route::get('/media/{media}/edit', [MediaController::class, 'edit'])->name('media.edit');
    Route::put('/media/{media}', [MediaController::class, 'update'])->name('media.update');
    Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');


    // projets
    Route::get('/projets', [ProjetController::class, 'index'])->name('projets.index');
    Route::get('/projets/create', [ProjetController::class, 'create'])->name('projets.create');
    Route::post('/projets', [ProjetController::class, 'store'])->name('projets.store');
    Route::get('/projets/{projet}/edit', [ProjetController::class, 'edit'])->name('projets.edit');
    Route::put('/projets/{projet}', [ProjetController::class, 'update'])->name('projets.update');
    Route::delete('/projets/{projet}', [ProjetController::class, 'destroy'])->name('projets.destroy');

});




// ROUTES BACKEND (ADMIN)
Route::prefix('admin')->name('admin.')->group(function () {
    // Page d'accueil du backend
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});
    
    Route::get('/admin', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

    // Ressources backend
    Route::resource('actualites', ActualiteController::class);
    // Ajouter d'autres resources si nécessaire...

// Route::prefix('admin')->name('admin.')->group(function () {
//     Route::get('/publication', [PublicationController::class, 'index'])->name('publication.index');
// });