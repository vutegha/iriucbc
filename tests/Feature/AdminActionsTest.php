<?php

namespace Tests\Feature;

use App\Models\Actualite;
use App\Models\Auteur;
use App\Models\Newsletter;
use App\Models\Publication;
use App\Models\Rapport;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Exercises admin actions against a minimal in-memory schema
 * (only the columns these actions touch).
 */
class AdminActionsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('services', function (Blueprint $t) {
            $t->id(); $t->string('nom')->nullable(); $t->string('nom_menu')->nullable(); $t->string('slug')->nullable();
            $t->boolean('is_published')->default(false); $t->boolean('show_in_menu')->default(false); $t->timestamps();
        });
        Schema::create('auteurs', function (Blueprint $t) {
            $t->id(); $t->string('nom'); $t->string('prenom')->nullable(); $t->string('email')->nullable();
            $t->string('institution')->nullable(); $t->timestamps();
        });
        Schema::create('newsletters', function (Blueprint $t) {
            $t->id(); $t->string('email')->unique(); $t->string('nom')->nullable(); $t->string('token')->nullable();
            $t->boolean('actif')->default(true); $t->text('preferences')->nullable(); $t->timestamps();
        });
        foreach (['actualites', 'publications'] as $table) {
            Schema::create($table, function (Blueprint $t) {
                $t->id(); $t->string('titre'); $t->string('slug')->nullable(); $t->text('resume')->nullable();
                $t->unsignedBigInteger('categorie_id')->nullable();
                $t->boolean('en_vedette')->default(false); $t->boolean('a_la_une')->default(false);
                $t->boolean('is_published')->default(false); $t->timestamp('published_at')->nullable();
                $t->unsignedBigInteger('published_by')->nullable(); $t->timestamps();
            });
        }
        Schema::create('rapports', function (Blueprint $t) {
            $t->id(); $t->string('titre'); $t->string('slug')->nullable(); $t->string('fichier')->nullable();
            $t->boolean('is_published')->default(false); $t->timestamps();
        });
    }

    /** A signed-in account that passes the admin gate; $allowed decides its abilities. */
    private function signIn(bool $allowed = true): void
    {
        $user = new class extends User {
            public bool $allowed = true;

            public function checkPermissionTo($permission, $guardName = null): bool
            {
                return $this->allowed;
            }

            public function hasRole($roles, ?string $guard = null): bool
            {
                return false;
            }

            public function hasAnyRole(...$roles): bool
            {
                return false;
            }
        };
        $user->forceFill(['id' => 1, 'name' => 'Test', 'email' => 'test@example.org', 'active' => true]);
        $user->allowed = $allowed;
        $user->setRelation('roles', collect(['role']));
        $user->setRelation('permissions', collect());

        $this->be($user);
    }

    public function test_author_search_returns_matching_authors_as_json(): void
    {
        $this->signIn();
        Auteur::forceCreate(['nom' => 'Kambale', 'prenom' => 'Jean', 'email' => 'jean@example.org', 'institution' => 'UCBC']);
        Auteur::forceCreate(['nom' => 'Mumbere', 'prenom' => 'Anne']);

        $this->getJson('/admin/auteurs/search?q=kamb')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.nom', 'Jean Kambale')
            ->assertJsonPath('0.institution', 'UCBC');

        // Too short a query, and a wildcard, return nothing rather than everything.
        $this->getJson('/admin/auteurs/search?q=k')->assertOk()->assertJsonCount(0);
        $this->getJson('/admin/auteurs/search?q=%25%25')->assertOk()->assertJsonCount(0);
    }

    public function test_newsletter_subscriber_can_be_added_and_edited(): void
    {
        $this->signIn();

        $this->get('/admin/newsletter/create')->assertOk();

        $this->post('/admin/newsletter', ['email' => 'Nouveau@Example.org'])
            ->assertRedirect(route('admin.newsletter.index'));
        $subscriber = Newsletter::where('email', 'nouveau@example.org')->firstOrFail();
        $this->assertNotEmpty($subscriber->token);

        // Duplicate address is refused.
        $this->post('/admin/newsletter', ['email' => 'nouveau@example.org'])->assertSessionHasErrors('email');

        $this->get("/admin/newsletter/{$subscriber->id}/edit")->assertOk();
        $this->put("/admin/newsletter/{$subscriber->id}", ['email' => 'change@example.org'])
            ->assertRedirect(route('admin.newsletter.index'));
        $this->assertSame('change@example.org', $subscriber->fresh()->email);
    }

    public function test_news_and_publication_front_page_toggles(): void
    {
        $this->signIn();
        $actualite = Actualite::forceCreate(['titre' => 'Une actualité', 'slug' => 'une-actualite']);
        $publication = Publication::forceCreate(['titre' => 'Une publication', 'slug' => 'une-publication']);

        $this->post("/admin/actualite/{$actualite->getRouteKey()}/toggle-une")->assertRedirect();
        $this->assertTrue((bool) $actualite->fresh()->a_la_une);
        $this->post("/admin/actualite/{$actualite->getRouteKey()}/toggle-une")->assertRedirect();
        $this->assertFalse((bool) $actualite->fresh()->a_la_une);

        $this->put("/admin/actualite/{$actualite->getRouteKey()}/updatefeatures", ['en_vedette' => 1, 'a_la_une' => 1])->assertRedirect();
        $this->assertTrue((bool) $actualite->fresh()->en_vedette);

        $this->post("/admin/publication/{$publication->getRouteKey()}/toggle-une")->assertRedirect();
        $this->assertTrue((bool) $publication->fresh()->a_la_une);
    }

    public function test_pending_moderation_pages_list_unpublished_items(): void
    {
        $this->signIn();
        Actualite::forceCreate(['titre' => 'Brouillon actu', 'slug' => 'brouillon-actu']);
        Actualite::forceCreate(['titre' => 'Actu en ligne', 'slug' => 'actu-en-ligne'])->forceFill(['is_published' => true])->save();

        $this->get('/admin/actualite/pending-moderation')
            ->assertOk()
            ->assertSee('Brouillon actu')
            ->assertDontSee('Actu en ligne');
    }

    public function test_report_file_download_and_preview(): void
    {
        $this->signIn();
        Storage::fake('public');
        Storage::disk('public')->put('assets/rapports/etude.pdf', '%PDF-1.4 test');
        $rapport = Rapport::forceCreate(['titre' => 'Étude 2025', 'slug' => 'etude-2025', 'fichier' => 'assets/rapports/etude.pdf']);
        $sansFichier = Rapport::forceCreate(['titre' => 'Sans fichier', 'slug' => 'sans-fichier']);

        $this->get("/admin/rapports/{$rapport->getRouteKey()}/download")
            ->assertOk()
            ->assertHeader('Content-Disposition', 'attachment; filename=etude-2025.pdf');

        $preview = $this->get("/admin/rapports/{$rapport->getRouteKey()}/preview")->assertOk();
        $this->assertStringContainsString('inline', $preview->headers->get('Content-Disposition'));

        $this->get("/admin/rapports/{$sansFichier->getRouteKey()}/preview")->assertNotFound();
    }

    public function test_actions_are_refused_without_the_permission(): void
    {
        $this->signIn(allowed: false);
        $actualite = Actualite::forceCreate(['titre' => 'Une actualité', 'slug' => 'une-actualite']);

        $this->getJson('/admin/auteurs/search?q=kamb')->assertForbidden();
        $this->post('/admin/newsletter', ['email' => 'x@example.org'])->assertForbidden();
        $this->post("/admin/actualite/{$actualite->getRouteKey()}/toggle-une")->assertForbidden();
        $this->assertFalse((bool) $actualite->fresh()->a_la_une);
        $this->assertSame(0, Newsletter::count());
    }
}
