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
use App\Http\Controllers\Admin\EvenementController;
use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\NewsletterController as PublicNewsletterController;
use App\Http\Controllers\Admin\JobOfferController;
use App\Http\Controllers\Admin\JobApplicationController;


Route::get('/', [SiteController::class, 'index'])->name('site.home');
Route::get('/galerie', [SiteController::class, 'galerie'])->name('site.galerie');
Route::get('/service', [SiteController::class, 'services'])->name('site.services');
Route::get('/service/{slug}', [SiteController::class, 'serviceshow'])->name('site.service.show');
Route::get('/service/{slug}/projets', [SiteController::class, 'serviceProjects'])->name('site.service.projets');
Route::get('/service/{slug}/actualites', [SiteController::class, 'serviceActualites'])->name('site.service.actualites');
Route::get('/projets', [SiteController::class, 'projets'])->name('site.projets');
Route::get('/projet/{slug}', [SiteController::class, 'projetShow'])->name('site.projet.show');
Route::get('/publications', [SiteController::class, 'publications'])->name('site.publications');
Route::get('/publications/{slug}', [SiteController::class, 'publicationShow'])->name('publication.show');
Route::get('/actualites', [SiteController::class, 'actualites'])->name('site.actualites');
Route::get('/convert', [SiteController::class, 'convertirImage'])->name('site.convert');
Route::get('/actualite/{slug}', [SiteController::class, 'actualiteShow'])->name('site.actualite.show');
Route::get('/actualite-id/{id}', [SiteController::class, 'actualiteShowById'])->name('site.actualite.id');
Route::get('/evenement/{id}', [SiteController::class, 'evenementShow'])->name('site.evenement.show');
Route::get('/contact', [SiteController::class, 'contact'])->name('site.contact');
Route::post('/contact', [SiteController::class, 'storeContact'])->name('site.contact.store');
Route::get('/work-with-us', [SiteController::class, 'workWithUs'])->name('site.work-with-us');
Route::get('/travaillez-avec-nous', [SiteController::class, 'workWithUs'])->name('site.work-with-us');
Route::get('/travailler avec nous', function() {
    return redirect()->route('site.work-with-us');
});
Route::get('/travaillez avec nous', function() {
    return redirect()->route('site.work-with-us');
});
Route::get('/travailler-avec-nous', function() {
    return redirect()->route('site.work-with-us');
});
Route::get('/emploi', function() {
    return redirect()->route('site.work-with-us');
});
Route::get('/emplois', function() {
    return redirect()->route('site.work-with-us');
});
Route::get('/carriere', function() {
    return redirect()->route('site.work-with-us');
});
Route::get('/carrieres', function() {
    return redirect()->route('site.work-with-us');
});
Route::get('/partenariats', [SiteController::class, 'partenariats'])->name('site.partenariats');
Route::get('/jobs/{job}/apply', [SiteController::class, 'showJobApplication'])->name('site.job.apply');
Route::post('/jobs/{job}/apply', [SiteController::class, 'submitJobApplication'])->name('site.job.apply.submit');
Route::get('/jobs/{job}/download', [SiteController::class, 'downloadJobDocument'])->name('site.job.download');
Route::post('/newsletter-subscribe', [SiteController::class, 'subscribeNewsletter'])->name('site.newsletter.subscribe');
Route::get('/recherche', [SiteController::class, 'search'])->name('site.search');
Route::get('/convert-image/{publication}', [SiteController::class, 'convertirImageUnique'])->name('publications.convert.single');



// route backend


// Groupe d'administration (facultatif à adapter selon tes middleware ou préfixes)
Route::prefix('admin')->name('admin.')->group(function () {
    


    // Actualite
    Route::get('/actualite', [ActualiteController::class, 'index'])->name('actualite.index');
    Route::get('/actualite/create', [ActualiteController::class, 'create'])->name('actualite.create');
    Route::post('/actualite', [ActualiteController::class, 'store'])->name('actualite.store');
    // Route::get('/actualite/{id}/edit', [ActualiteController::class, 'edit'])->name('actualite.edit');
    Route::get('/actualite/{actualite}/edit', [ActualiteController::class, 'edit'])->name('actualite.edit');

    // Route::put('/actualite/{id}', [ActualiteController::class, 'update'])->name('actualite.update');
    Route::put('/actualite/{actualite}', [ActualiteController::class, 'update'])->name('actualite.update');

    Route::delete('/actualite/{actualite}', [ActualiteController::class, 'destroy'])->name('actualite.destroy');
    // Route::put('/actualite/{id}/updatefeatures', [ActualiteController::class, 'updateFeatures'])->name('actualite.updatefeatures');
    Route::put('/actualite/{actualite}/updatefeatures', [ActualiteController::class, 'updateFeatures'])->name('actualite.updatefeatures');
    Route::get('/actualite/{id}/show', [ActualiteController::class, 'show'])->name('actualite.show');
    Route::post('/actualite/{actualite}/toggle-une', [ActualiteController::class, 'toggleUne'])->name('actualite.toggle-une');
    
    // Routes de modération pour actualités
    Route::post('/actualite/{actualite}/publish', [ActualiteController::class, 'publish'])->name('actualite.publish');
    Route::post('/actualite/{actualite}/unpublish', [ActualiteController::class, 'unpublish'])->name('actualite.unpublish');
    Route::get('/actualite/pending-moderation', [ActualiteController::class, 'pendingModeration'])->name('actualite.pending');

    // publication
    Route::get('/publication', [PublicationController::class, 'index'])->name('publication.index');
    Route::get('/publication/create', [PublicationController::class, 'create'])->name('publication.create');
    Route::post('/publication', [PublicationController::class, 'store'])->name('publication.store');
    // Route::get('/publication/{id}/edit', [PublicationController::class, 'edit'])->name('publication.edit');
    Route::get('/publication/{publication}/edit', [PublicationController::class, 'edit'])->name('publication.edit');

    // Route::put('/publication/{id}', [PublicationController::class, 'update'])->name('publication.update');
    Route::put('/publication/{publication}', [PublicationController::class, 'update'])->name('publication.update');

    Route::delete('/publication/{publication}', [PublicationController::class, 'destroy'])->name('publication.destroy');
    // Route::put('/publication/{id}/updatefeatures', [PublicationController::class, 'updateFeatures'])->name('publication.updatefeatures');
    Route::put('/publication/{publication}/updatefeatures', [PublicationController::class, 'updateFeatures'])->name('publication.updatefeatures');
    Route::get('/publication/{id}/show', [PublicationController::class, 'show'])->name('publication.show');
    Route::post('/publication/{publication}/toggle-une', [PublicationController::class, 'toggleUne'])->name('publication.toggle-une');
    
    // Routes de modération pour publications (protégées)
    Route::middleware(['can_moderate'])->group(function () {
        Route::post('/publication/{publication}/publish', [PublicationController::class, 'publish'])->name('publication.publish');
        Route::post('/publication/{publication}/unpublish', [PublicationController::class, 'unpublish'])->name('publication.unpublish');
        Route::get('/publication/pending-moderation', [PublicationController::class, 'pendingModeration'])->name('publication.pending');
    });
    
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
    Route::get('/service/{service}/show', [ServiceController::class, 'show'])->name('service.show');
    Route::get('/service/{service}/edit', [ServiceController::class, 'edit'])->name('service.edit');
    Route::put('/service/{service}', [ServiceController::class, 'update'])->name('service.update');
    Route::delete('/service/{service}', [ServiceController::class, 'destroy'])->name('service.destroy');
    
    // Routes de modération pour services
    Route::post('/service/{service}/publish', [ServiceController::class, 'publish'])->name('service.publish');
    Route::post('/service/{service}/unpublish', [ServiceController::class, 'unpublish'])->name('service.unpublish');
    Route::post('/service/{service}/toggle-menu', [ServiceController::class, 'toggleMenu'])->name('service.toggle-menu');
    Route::get('/service/pending-moderation', [ServiceController::class, 'pendingModeration'])->name('service.pending');

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
    Route::get('/rapports/{rapport}', [RapportController::class, 'show'])->name('rapports.show');
    Route::get('/rapports/{rapport}/edit', [RapportController::class, 'edit'])->name('rapports.edit');
    Route::put('/rapports/{rapport}', [RapportController::class, 'update'])->name('rapports.update');
    Route::delete('/rapports/{rapport}', [RapportController::class, 'destroy'])->name('rapports.destroy');
    
    // Routes de modération pour rapports
    Route::post('/rapports/{rapport}/publish', [RapportController::class, 'publish'])->name('rapports.publish');
    Route::post('/rapports/{rapport}/unpublish', [RapportController::class, 'unpublish'])->name('rapports.unpublish');
    Route::get('/rapports/pending-moderation', [RapportController::class, 'pendingModeration'])->name('rapports.pending');

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
    Route::get('/projets/{projet}', [ProjetController::class, 'show'])->name('projets.show');
    Route::get('/projets/{projet}/edit', [ProjetController::class, 'edit'])->name('projets.edit');
    Route::put('/projets/{projet}', [ProjetController::class, 'update'])->name('projets.update');
    Route::delete('/projets/{projet}', [ProjetController::class, 'destroy'])->name('projets.destroy');
    
    // Routes de modération pour projets
    Route::post('/projets/{projet}/publish', [ProjetController::class, 'publish'])->name('projets.publish');
    Route::post('/projets/{projet}/unpublish', [ProjetController::class, 'unpublish'])->name('projets.unpublish');
    Route::get('/projets/pending-moderation', [ProjetController::class, 'pendingModeration'])->name('projets.pending');

    // Job Offers - Gestion des offres d'emploi
    Route::resource('job-offers', JobOfferController::class);
    Route::post('/job-offers/{jobOffer}/duplicate', [JobOfferController::class, 'duplicate'])->name('job-offers.duplicate');
    Route::post('/job-offers/{jobOffer}/change-status', [JobOfferController::class, 'changeStatus'])->name('job-offers.change-status');
    Route::post('/job-offers/{jobOffer}/toggle-featured', [JobOfferController::class, 'toggleFeatured'])->name('job-offers.toggle-featured');
    Route::get('/job-offers-statistics', [JobOfferController::class, 'statistics'])->name('job-offers.statistics');
    
    // Job Applications - Gestion des candidatures
    Route::get('/job-applications', [JobApplicationController::class, 'index'])->name('job-applications.index');
    Route::get('/job-applications/{application}', [JobApplicationController::class, 'show'])->name('job-applications.show');
    Route::patch('/job-applications/{application}/status', [JobApplicationController::class, 'updateStatus'])->name('job-applications.update-status');
    Route::get('/job-applications/{application}/download-cv', [JobApplicationController::class, 'downloadCV'])->name('job-applications.download-cv');
    Route::get('/job-applications/{application}/download-portfolio', [JobApplicationController::class, 'downloadPortfolio'])->name('job-applications.download-portfolio');
    Route::delete('/job-applications/{application}', [JobApplicationController::class, 'destroy'])->name('job-applications.destroy');
    Route::get('/job-applications-export', [JobApplicationController::class, 'export'])->name('job-applications.export');
    Route::post('/job-applications/bulk-review', [JobApplicationController::class, 'bulkReview'])->name('job-applications.bulk-review');
    Route::get('/job-applications-statistics', [JobApplicationController::class, 'statistics'])->name('job-applications.statistics');

});




// ROUTES BACKEND (ADMIN)
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Logout route
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
    
    // Gestion des contacts
    Route::get('/contacts', [\App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'show'])->name('contacts.show');
    Route::patch('/contacts/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'update'])->name('contacts.update');
    Route::delete('/contacts/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contacts.destroy');
    
    // Gestion Événements
    Route::resource('evenements', EvenementController::class);
    
    // Routes de modération pour événements
    Route::post('/evenements/{evenement}/publish', [EvenementController::class, 'publish'])->name('evenements.publish');
    Route::post('/evenements/{evenement}/unpublish', [EvenementController::class, 'unpublish'])->name('evenements.unpublish');
    Route::get('/evenements/pending-moderation', [EvenementController::class, 'pendingModeration'])->name('evenements.pending');
    
    // Gestion Newsletter
    Route::get('/newsletter/export', [NewsletterController::class, 'export'])->name('newsletter.export');
    Route::get('/newsletter', [NewsletterController::class, 'index'])->name('newsletter.index');
    Route::get('/newsletter/{newsletter}', [NewsletterController::class, 'show'])->name('newsletter.show');
    Route::patch('/newsletter/{newsletter}/toggle', [NewsletterController::class, 'toggle'])->name('newsletter.toggle');
    Route::delete('/newsletter/{newsletter}', [NewsletterController::class, 'destroy'])->name('newsletter.destroy');
});

// Routes Newsletter publiques
Route::prefix('newsletter')->name('newsletter.')->group(function () {
    Route::get('/subscribe', function() { return view('newsletter.subscribe'); })->name('subscribe');
    Route::post('/subscribe', [PublicNewsletterController::class, 'subscribe'])->name('subscribe.post');
    Route::get('/preferences/{token}', [PublicNewsletterController::class, 'preferences'])->name('preferences');
    Route::post('/preferences/{token}', [PublicNewsletterController::class, 'updatePreferences'])->name('preferences.update');
    Route::get('/unsubscribe/{token}', [PublicNewsletterController::class, 'unsubscribe'])->name('unsubscribe');
    Route::post('/unsubscribe/{token}', [PublicNewsletterController::class, 'confirmUnsubscribe'])->name('unsubscribe.confirm');
});

    // Ressources backend
    // Ajouter d'autres resources si nécessaire...

// Route::prefix('admin')->name('admin.')->group(function () {
//     Route::get('/publication', [PublicationController::class, 'index'])->name('publication.index');
// });