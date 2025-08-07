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
use App\Http\Controllers\Admin\EmailTestController;
use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\NewsletterController as PublicNewsletterController;
use App\Http\Controllers\Admin\JobOfferController;
use App\Http\Controllers\Admin\JobApplicationController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PartenaireController;
use App\Http\Controllers\Admin\SocialLinkController;

// ====================================================================
// ROUTES D'AUTHENTIFICATION (STANDARDS LARAVEL)
// ====================================================================

// Routes guest (authentification) - URLs standard Laravel
Route::middleware(['guest'])->group(function () {
    // Connexion
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    // Inscription
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    
    // Réinitialisation de mot de passe
    Route::get('/password/reset', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [AuthController::class, 'reset'])->name('password.update');
});

// Route de déconnexion (utilisateurs authentifiés) - Reste dans l'espace admin
Route::middleware(['auth'])->group(function () {
    Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
});

// ====================================================================
// ROUTES FRONTEND (PUBLIQUES - SANS AUTHENTIFICATION)
// ====================================================================

// Routes principales du site public
Route::get('/', [SiteController::class, 'index'])->name('site.home');
Route::get('/galerie', [SiteController::class, 'galerie'])->name('site.galerie');
Route::get('/media/download/{id}', [SiteController::class, 'downloadMedia'])->name('site.media.download');

// Services
Route::get('/service', [SiteController::class, 'services'])->name('site.services');
Route::get('/service/{slug}', [SiteController::class, 'serviceshow'])->name('site.service.show');
Route::get('/service/{slug}/projets', [SiteController::class, 'serviceProjects'])->name('site.service.projets');
Route::get('/service/{slug}/actualites', [SiteController::class, 'serviceActualites'])->name('site.service.actualites');

// Projets
Route::get('/projets', [SiteController::class, 'projets'])->name('site.projets');
Route::get('/projet/{slug}', [SiteController::class, 'projetShow'])->name('site.projet.show');

// Publications
Route::get('/publications', [SiteController::class, 'publications'])->name('site.publications');
Route::get('/publications/{slug}', [SiteController::class, 'publicationShow'])->name('publication.show');
Route::get('/convert-image/{publication}', [SiteController::class, 'convertirImageUnique'])->name('publications.convert.single');

// Actualités
Route::get('/actualites', [SiteController::class, 'actualites'])->name('site.actualites');
Route::get('/actualite/{slug}', [SiteController::class, 'actualiteShow'])->name('site.actualite.show');
Route::get('/actualite-id/{id}', [SiteController::class, 'actualiteShowById'])->name('site.actualite.id');

// Événements
Route::get('/evenements', [SiteController::class, 'evenements'])->name('site.evenements');
Route::get('/evenement/{slug}', [SiteController::class, 'evenementShow'])->name('site.evenement.show');

// À propos
Route::get('/about', [SiteController::class, 'about'])->name('site.about');
Route::get('/a-propos', fn() => redirect()->route('site.about'));

// Contact
Route::get('/contact', [SiteController::class, 'contact'])->name('site.contact');
Route::post('/contact', [SiteController::class, 'storeContact'])->name('site.contact.store');

// Travaillez avec nous et emploi (redirections)
Route::get('/work-with-us', [SiteController::class, 'workWithUs'])->name('site.work-with-us');
Route::get('/travaillez-avec-nous', [SiteController::class, 'workWithUs']);
Route::get('/travailler avec nous', fn() => redirect()->route('site.work-with-us'));
Route::get('/travaillez avec nous', fn() => redirect()->route('site.work-with-us'));
Route::get('/travailler-avec-nous', fn() => redirect()->route('site.work-with-us'));
Route::get('/emploi', fn() => redirect()->route('site.work-with-us'));
Route::get('/emplois', fn() => redirect()->route('site.work-with-us'));
Route::get('/carriere', fn() => redirect()->route('site.work-with-us'));
Route::get('/carrieres', fn() => redirect()->route('site.work-with-us'));

// Candidatures d'emploi
Route::get('/jobs/{job}/apply', [SiteController::class, 'showJobApplication'])->name('site.job.apply');
Route::post('/jobs/{job}/apply', [SiteController::class, 'submitJobApplication'])->name('site.job.apply.submit');
Route::get('/jobs/{job}/download', [SiteController::class, 'downloadJobDocument'])->name('site.job.download');

// Autres pages
Route::get('/partenariats', [SiteController::class, 'partenariats'])->name('site.partenariats');
Route::get('/recherche', [SiteController::class, 'search'])->name('site.search');
Route::get('/convert', [SiteController::class, 'convertirImage'])->name('site.convert');

// Newsletter publique
Route::post('/newsletter-subscribe', [SiteController::class, 'subscribeNewsletter'])->name('site.newsletter.subscribe');

// Routes Newsletter publiques détaillées
Route::prefix('newsletter')->name('newsletter.')->group(function () {
    Route::get('/subscribe', fn() => view('newsletter.subscribe'))->name('subscribe');
    Route::post('/subscribe', [PublicNewsletterController::class, 'subscribe'])->name('subscribe.post');
    Route::get('/preferences/{token}', [PublicNewsletterController::class, 'preferences'])->name('preferences');
    Route::put('/preferences/{token}', [PublicNewsletterController::class, 'updatePreferences'])->name('preferences.update');
    Route::get('/unsubscribe/{token}', [PublicNewsletterController::class, 'unsubscribe'])->name('unsubscribe');
    Route::post('/unsubscribe/{token}', [PublicNewsletterController::class, 'confirmUnsubscribe'])->name('unsubscribe.confirm');
    Route::get('/resubscribe/{token}', [PublicNewsletterController::class, 'resubscribe'])->name('resubscribe');
});

// ====================================================================
// ROUTES ADMIN (PROTÉGÉES PAR AUTHENTIFICATION)
// ====================================================================

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard principal
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Test d'email (admin seulement)
    Route::middleware(['can:viewAny,App\Models\User'])->prefix('email-test')->name('email-test.')->group(function () {
        Route::get('/', [EmailTestController::class, 'index'])->name('index');
        Route::post('/send', [EmailTestController::class, 'send'])->name('send');
        Route::get('/config', [EmailTestController::class, 'testConfig'])->name('config');
        Route::post('/connection', [EmailTestController::class, 'testConnection'])->name('connection');
        Route::get('/password-reset', [EmailTestController::class, 'testPasswordReset'])->name('password-reset');
    });
    
    // Gestion des utilisateurs (admin seulement)
    Route::middleware(['can:manage_users'])->prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('/{user}/permissions', [UserController::class, 'managePermissions'])->name('manage-permissions');
        Route::put('/{user}/permissions', [UserController::class, 'updatePermissions'])->name('update-permissions');
    });

    // ================================
    // GESTION DES ACTUALITÉS
    // ================================
    Route::prefix('actualite')->name('actualite.')->group(function () {
        Route::get('/', [ActualiteController::class, 'index'])->name('index');
        Route::get('/create', [ActualiteController::class, 'create'])->name('create');
        Route::post('/', [ActualiteController::class, 'store'])->name('store');
        Route::get('/pending-moderation', [ActualiteController::class, 'pendingModeration'])->name('pending');
        Route::get('/view/{actualite}', [ActualiteController::class, 'show'])->name('show');
        Route::get('/{actualite}/edit', [ActualiteController::class, 'edit'])->name('edit');
        Route::put('/{actualite}', [ActualiteController::class, 'update'])->name('update');
        Route::delete('/{actualite}', [ActualiteController::class, 'destroy'])->name('destroy');
        Route::put('/{actualite}/updatefeatures', [ActualiteController::class, 'updateFeatures'])->name('updatefeatures');
        Route::post('/{actualite}/toggle-une', [ActualiteController::class, 'toggleUne'])->name('toggle-une');
        Route::post('/{actualite}/publish', [ActualiteController::class, 'publish'])->name('publish');
        Route::post('/{actualite}/unpublish', [ActualiteController::class, 'unpublish'])->name('unpublish');
        Route::patch('/{actualite}/moderate', [ActualiteController::class, 'moderate'])->name('moderate');
    });

    // ================================
    // GESTION DES PUBLICATIONS
    // ================================
    Route::prefix('publication')->name('publication.')->group(function () {
        Route::get('/', [PublicationController::class, 'index'])->name('index');
        Route::get('/create', [PublicationController::class, 'create'])->name('create');
        Route::post('/', [PublicationController::class, 'store'])->name('store');
        Route::get('/{publication}', [PublicationController::class, 'show'])->name('show');
        Route::get('/{publication}/edit', [PublicationController::class, 'edit'])->name('edit');
        Route::put('/{publication}', [PublicationController::class, 'update'])->name('update');
        Route::delete('/{publication}', [PublicationController::class, 'destroy'])->name('destroy');
        Route::put('/{publication}/updatefeatures', [PublicationController::class, 'updateFeatures'])->name('updatefeatures');
        Route::post('/{publication}/toggle-une', [PublicationController::class, 'toggleUne'])->name('toggle-une');
        
        // Routes de modération (protégées)
        Route::middleware(['can_moderate'])->group(function () {
            Route::post('/{publication}/publish', [PublicationController::class, 'publish'])->name('publish');
            Route::post('/{publication}/unpublish', [PublicationController::class, 'unpublish'])->name('unpublish');
            Route::get('/pending-moderation', [PublicationController::class, 'pendingModeration'])->name('pending');
        });
    });

    // ================================
    // GESTION DES AUTEURS
    // ================================
    
    // Routes AJAX pour auteurs (pour les modals de publication) - AVANT les routes génériques
    Route::prefix('auteurs')->name('auteurs.')->group(function () {
        Route::get('/search', [AuteurController::class, 'search'])->name('search');
        Route::post('/', [AuteurController::class, 'store'])->name('store-ajax');
    });

    // Routes CRUD classiques pour auteurs
    Route::prefix('auteur')->name('auteur.')->group(function () {
        Route::get('/', [AuteurController::class, 'index'])->name('index');
        Route::get('/create', [AuteurController::class, 'create'])->name('create');
        Route::post('/', [AuteurController::class, 'store'])->name('store');
        Route::get('/{auteur}/edit', [AuteurController::class, 'edit'])->name('edit');
        Route::put('/{auteur}', [AuteurController::class, 'update'])->name('update');
        Route::delete('/{auteur}', [AuteurController::class, 'destroy'])->name('destroy');
        Route::get('/{auteur}/show', [AuteurController::class, 'show'])->name('show');
    });

    // ================================
    // GESTION DES SERVICES
    // ================================
    Route::prefix('service')->name('service.')->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::get('/create', [ServiceController::class, 'create'])->name('create');
        Route::post('/', [ServiceController::class, 'store'])->name('store');
        Route::get('/{service}/show', [ServiceController::class, 'show'])->name('show');
        Route::get('/{service}/edit', [ServiceController::class, 'edit'])->name('edit');
        Route::put('/{service}', [ServiceController::class, 'update'])->name('update');
        Route::delete('/{service}', [ServiceController::class, 'destroy'])->name('destroy');
        
        // Routes de modération
        Route::post('/{service}/publish', [ServiceController::class, 'publish'])->name('publish');
        Route::post('/{service}/unpublish', [ServiceController::class, 'unpublish'])->name('unpublish');
        Route::post('/{service}/toggle-menu', [ServiceController::class, 'toggleMenu'])->name('toggle-menu');
        Route::get('/pending-moderation', [ServiceController::class, 'pendingModeration'])->name('pending');
    });

    // ================================
    // GESTION DES PARTENAIRES
    // ================================
    Route::prefix('partenaires')->name('partenaires.')->group(function () {
        Route::get('/', [PartenaireController::class, 'index'])->name('index');
        Route::get('/create', [PartenaireController::class, 'create'])->name('create');
        Route::post('/', [PartenaireController::class, 'store'])->name('store');
        Route::get('/{partenaire}', [PartenaireController::class, 'show'])->name('show');
        Route::get('/{partenaire}/edit', [PartenaireController::class, 'edit'])->name('edit');
        Route::put('/{partenaire}', [PartenaireController::class, 'update'])->name('update');
        Route::delete('/{partenaire}', [PartenaireController::class, 'destroy'])->name('destroy');
        Route::patch('/{partenaire}/toggle-publication', [PartenaireController::class, 'togglePublication'])->name('toggle-publication');
    });

    // ================================
    // GESTION DES LIENS SOCIAUX
    // ================================
    Route::prefix('social-links')->name('social-links.')->group(function () {
        Route::get('/', [SocialLinkController::class, 'index'])->name('index');
        Route::get('/create', [SocialLinkController::class, 'create'])->name('create');
        Route::post('/', [SocialLinkController::class, 'store'])->name('store');
        Route::get('/{socialLink}', [SocialLinkController::class, 'show'])->name('show');
        Route::get('/{socialLink}/edit', [SocialLinkController::class, 'edit'])->name('edit');
        Route::put('/{socialLink}', [SocialLinkController::class, 'update'])->name('update');
        Route::delete('/{socialLink}', [SocialLinkController::class, 'destroy'])->name('destroy');
        Route::patch('/{socialLink}/toggle-active', [SocialLinkController::class, 'toggleActive'])->name('toggle-active');
    });

    // ================================
    // GESTION DES NEWSLETTERS
    // ================================
    Route::prefix('newsletter')->name('newsletter.')->group(function () {
        Route::get('/', [NewsletterController::class, 'index'])->name('index');
        Route::get('/create', [NewsletterController::class, 'create'])->name('create');
        Route::post('/', [NewsletterController::class, 'store'])->name('store');
        Route::get('/{newsletter}/edit', [NewsletterController::class, 'edit'])->name('edit');
        Route::put('/{newsletter}', [NewsletterController::class, 'update'])->name('update');
        Route::delete('/{newsletter}', [NewsletterController::class, 'destroy'])->name('destroy');
        Route::get('/export', [NewsletterController::class, 'export'])->name('export');
        Route::get('/{newsletter}', [NewsletterController::class, 'show'])->name('show');
        Route::patch('/{newsletter}/toggle', [NewsletterController::class, 'toggle'])->name('toggle');
    });

    // ================================
    // GESTION DES CATÉGORIES
    // ================================
    
    // Routes AJAX pour catégories (pour les modals de publication) - AVANT les routes génériques
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::post('/', [CategorieController::class, 'store'])->name('store-ajax');
    });

    // Routes CRUD classiques pour catégories
    Route::prefix('categorie')->name('categorie.')->group(function () {
        Route::get('/', [CategorieController::class, 'index'])->name('index');
        Route::get('/create', [CategorieController::class, 'create'])->name('create');
        Route::post('/', [CategorieController::class, 'store'])->name('store');
        Route::get('/{categorie}/edit', [CategorieController::class, 'edit'])->name('edit');
        Route::put('/{categorie}', [CategorieController::class, 'update'])->name('update');
        Route::delete('/{categorie}', [CategorieController::class, 'destroy'])->name('destroy');
    });

    // ================================
    // GESTION DES RAPPORTS
    // ================================
    Route::prefix('rapports')->name('rapports.')->group(function () {
        Route::get('/', [RapportController::class, 'index'])->name('index');
        Route::get('/search', [RapportController::class, 'search'])->name('search');
        Route::get('/category/{categorieId?}', [RapportController::class, 'byCategory'])->name('category');
        Route::get('/create', [RapportController::class, 'create'])->name('create');
        Route::post('/', [RapportController::class, 'store'])->name('store');
        // Rediriger vers publication.show pour l'affichage des rapports
        Route::get('/{rapport}', function($rapport) {
            return redirect()->route('publication.show', ['slug' => $rapport]);
        })->name('show');
        Route::get('/{rapport}/download', [RapportController::class, 'download'])->name('download');
        Route::get('/{rapport}/preview', [RapportController::class, 'preview'])->name('preview');
        Route::get('/{rapport}/edit', [RapportController::class, 'edit'])->name('edit');
        Route::put('/{rapport}', [RapportController::class, 'update'])->name('update');
        Route::delete('/{rapport}', [RapportController::class, 'destroy'])->name('destroy');
        
        // Routes pour les actions rapides
        Route::post('/delete-multiple', [RapportController::class, 'deleteMultiple'])->name('delete-multiple');
        Route::get('/export', [RapportController::class, 'export'])->name('export');
        Route::get('/download-zip', [RapportController::class, 'downloadZip'])->name('download-zip');
        
        // Routes de modération (conservées pour compatibilité)
        Route::post('/{rapport}/publish', [RapportController::class, 'publish'])->name('publish');
        Route::post('/{rapport}/unpublish', [RapportController::class, 'unpublish'])->name('unpublish');
        Route::get('/pending-moderation', [RapportController::class, 'pendingModeration'])->name('pending');
    });

    // ================================
    // GESTION DES PROJETS
    // ================================
    Route::prefix('projets')->name('projets.')->group(function () {
        Route::get('/', [ProjetController::class, 'index'])->name('index');
        Route::post('/search', [ProjetController::class, 'search'])->name('search');
        Route::get('/create', [ProjetController::class, 'create'])->name('create');
        Route::post('/', [ProjetController::class, 'store'])->name('store');
        Route::get('/{projet}', [ProjetController::class, 'show'])->name('show');
        Route::get('/{projet}/edit', [ProjetController::class, 'edit'])->name('edit');
        Route::put('/{projet}', [ProjetController::class, 'update'])->name('update');
        Route::delete('/{projet}', [ProjetController::class, 'destroy'])->name('destroy');
        
        // Routes de modération
        Route::post('/{projet}/publish', [ProjetController::class, 'publish'])->name('publish');
        Route::post('/{projet}/unpublish', [ProjetController::class, 'unpublish'])->name('unpublish');
        Route::get('/pending-moderation', [ProjetController::class, 'pendingModeration'])->name('pending');
    });

    // ================================
    // GESTION DES ÉVÉNEMENTS
    // ================================
    Route::resource('evenements', EvenementController::class);
    Route::patch('/evenements/{evenement}/toggle-featured', [EvenementController::class, 'toggleFeatured'])->name('evenements.toggle-featured');
    Route::patch('/evenements/{evenement}/toggle-published', [EvenementController::class, 'togglePublished'])->name('evenements.toggle-published');

    // ================================
    // GESTION DES CONTACTS
    // ================================
    Route::prefix('contacts')->name('contacts.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ContactController::class, 'index'])->name('index');
        Route::get('/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'show'])->name('show');
        Route::patch('/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'update'])->name('update');
        Route::delete('/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('destroy');
    });

    // ================================
    // CONFIGURATION DES EMAILS
    // ================================
    Route::prefix('email-settings')->name('email-settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\EmailSettingController::class, 'index'])->name('index');
        Route::put('/{emailSetting}', [\App\Http\Controllers\Admin\EmailSettingController::class, 'update'])->name('update');
        Route::post('/{emailSetting}/add-email', [\App\Http\Controllers\Admin\EmailSettingController::class, 'addEmail'])->name('add-email');
        Route::delete('/{emailSetting}/remove-email', [\App\Http\Controllers\Admin\EmailSettingController::class, 'removeEmail'])->name('remove-email');
        Route::post('/test-email', [\App\Http\Controllers\Admin\EmailSettingController::class, 'testEmail'])->name('test-email');
    });

    // ================================
    // GESTION DES OFFRES D'EMPLOI
    // ================================
    Route::resource('job-offers', JobOfferController::class);
    Route::post('/job-offers/{jobOffer}/duplicate', [JobOfferController::class, 'duplicate'])->name('job-offers.duplicate');
    Route::post('/job-offers/{jobOffer}/change-status', [JobOfferController::class, 'changeStatus'])->name('job-offers.change-status');
    Route::post('/job-offers/{jobOffer}/toggle-featured', [JobOfferController::class, 'toggleFeatured'])->name('job-offers.toggle-featured');
    Route::get('/job-offers-statistics', [JobOfferController::class, 'statistics'])->name('job-offers.statistics');
    
    // ================================
    // GESTION DES CANDIDATURES
    // ================================
    Route::prefix('job-applications')->name('job-applications.')->group(function () {
        Route::get('/', [JobApplicationController::class, 'index'])->name('index');
        Route::get('/{application}', [JobApplicationController::class, 'show'])->name('show');
        Route::patch('/{application}/status', [JobApplicationController::class, 'updateStatus'])->name('update-status');
        Route::get('/{application}/download-cv', [JobApplicationController::class, 'downloadCV'])->name('download-cv');
        Route::get('/{application}/download-portfolio', [JobApplicationController::class, 'downloadPortfolio'])->name('download-portfolio');
        Route::delete('/{application}', [JobApplicationController::class, 'destroy'])->name('destroy');
        Route::get('/export', [JobApplicationController::class, 'export'])->name('export');
        Route::post('/bulk-review', [JobApplicationController::class, 'bulkReview'])->name('bulk-review');
        Route::get('/statistics', [JobApplicationController::class, 'statistics'])->name('statistics');
    });

    // ================================
    // GESTION DES MÉDIAS (dans le groupe admin)
    // ================================
    Route::prefix('media')->name('media.')->group(function () {
        Route::get('/', [MediaController::class, 'index'])->name('index');
        Route::get('/create', [MediaController::class, 'create'])->name('create');
        Route::post('/', [MediaController::class, 'store'])->name('store');
        
        // For CKEditor media integration - DOIT ÊTRE AVANT LES ROUTES AVEC PARAMÈTRES
        Route::get('/list', [MediaController::class, 'list'])->name('list');
        Route::post('/upload', [MediaController::class, 'upload'])->name('upload');
        
        Route::get('/{media}', [MediaController::class, 'show'])->name('show');
        Route::get('/{media}/edit', [MediaController::class, 'edit'])->name('edit');
        Route::put('/{media}', [MediaController::class, 'update'])->name('update');
        Route::delete('/{media}', [MediaController::class, 'destroy'])->name('destroy');
        
        // Actions de modération
        Route::post('/{media}/approve', [MediaController::class, 'approve'])->name('approve');
        Route::post('/{media}/reject', [MediaController::class, 'reject'])->name('reject');
        Route::post('/{media}/publish', [MediaController::class, 'publish'])->name('publish');
        Route::post('/{media}/unpublish', [MediaController::class, 'unpublish'])->name('unpublish');
        
        // Actions utilitaires
        Route::get('/{media}/download', [MediaController::class, 'download'])->name('download');
        Route::get('/{media}/copy-link', [MediaController::class, 'copyLink'])->name('copyLink');

    });
});