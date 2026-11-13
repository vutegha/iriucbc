<?php

namespace Tests\Feature;

use App\Models\JobApplication;
use App\Models\JobOffer;
use App\Models\Projet;
use App\Models\Rapport;
use App\Models\Service;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

/**
 * Renders the moderation and statistics templates with in-memory data
 * (no database), to catch template errors.
 */
class AdminViewsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Every view triggers the public menu composer, which reads published
        // services. Give it an empty table in the in-memory test database.
        \Illuminate\Support\Facades\Schema::create('services', function ($table) {
            $table->id();
            $table->string('nom')->nullable();
            $table->string('nom_menu')->nullable();
            $table->string('slug')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('show_in_menu')->default(false);
            $table->timestamps();
        });

        // Normally provided by the session middleware.
        view()->share('errors', new \Illuminate\Support\ViewErrorBag());
    }

    private function paginate(array $items): LengthAwarePaginator
    {
        return new LengthAwarePaginator($items, count($items), 10, 1, ['path' => '/admin']);
    }

    private function pendingParams(string $prefix, string $titleField, string $ability, array $items): array
    {
        return [
            'items' => $this->paginate($items),
            'heading' => 'En attente',
            'itemLabel' => 'éléments',
            'routePrefix' => $prefix,
            'titleField' => $titleField,
            'publishAbility' => $ability,
        ];
    }

    public function test_pending_list_shows_items_and_actions_for_an_authorised_user(): void
    {
        // A user who is granted every ability, without touching the database.
        $user = new class(['name' => 'Test', 'email' => 'test@example.org']) extends \App\Models\User {
            public function checkPermissionTo($permission, $guardName = null): bool
            {
                return true;
            }
        };
        $this->be($user);

        $cases = [
            ['admin.rapports', 'titre', 'publish', (new Rapport())->forceFill(['titre' => 'Rapport <b>A</b>', 'slug' => 'rapport-a', 'created_at' => now()])],
            ['admin.service', 'nom', 'publish', (new Service())->forceFill(['nom' => 'Service A', 'slug' => 'service-a', 'created_at' => now()])],
            ['admin.projets', 'nom', 'moderate', (new Projet())->forceFill(['nom' => 'Projet A', 'slug' => 'projet-a', 'created_at' => now()])],
        ];

        foreach ($cases as [$prefix, $field, $ability, $model]) {
            $html = view('admin.partials.pending-moderation', $this->pendingParams($prefix, $field, $ability, [$model]))->render();

            $this->assertStringContainsString(route($prefix . '.publish', $model), $html, $prefix);
            $this->assertStringContainsString(route($prefix . '.edit', $model), $html, $prefix);
            $this->assertStringContainsString('Publier', $html, $prefix);
        }

        // Titles are escaped.
        $html = view('admin.partials.pending-moderation', $this->pendingParams('admin.rapports', 'titre', 'publish', [$cases[0][3]]))->render();
        $this->assertStringContainsString('Rapport &lt;b&gt;A&lt;/b&gt;', $html);
    }

    public function test_pending_list_hides_actions_from_guests_and_handles_empty_lists(): void
    {
        $model = (new Rapport())->forceFill(['titre' => 'Rapport A', 'slug' => 'rapport-a', 'created_at' => now()]);

        $html = view('admin.partials.pending-moderation', $this->pendingParams('admin.rapports', 'titre', 'publish', [$model]))->render();
        $this->assertStringContainsString('Rapport A', $html);
        $this->assertStringNotContainsString('Publier', $html);

        $empty = view('admin.partials.pending-moderation', $this->pendingParams('admin.rapports', 'titre', 'publish', []))->render();
        $this->assertStringContainsString('Rien à modérer', $empty);
    }

    public function test_job_application_statistics_content_renders(): void
    {
        $row = (new JobApplication())->forceFill(['job_offer_id' => 1, 'count' => 4]);
        $row->setRelation('jobOffer', (new JobOffer())->forceFill(['id' => 1, 'title' => 'Chargé de projet']));
        $orphan = (new JobApplication())->forceFill(['job_offer_id' => 2, 'count' => 1]);
        $orphan->setRelation('jobOffer', null);

        $sections = view('admin.job-applications.statistics', [
            'stats' => [
                'total_applications' => 5, 'pending_applications' => 2, 'reviewed_applications' => 1,
                'accepted_applications' => 1, 'rejected_applications' => 1, 'recent_applications' => 3,
            ],
            'applicationsByJob' => collect([$row, $orphan]),
            'applicationsByMonth' => [3 => 2, 11 => 3],
        ])->renderSections();

        $content = $sections['content'];
        $this->assertStringContainsString('Chargé de projet', $content);
        $this->assertStringContainsString('Offre supprimée', $content);
        $this->assertStringContainsString('width: 100%', $content);

        // Also with no data at all.
        $empty = view('admin.job-applications.statistics', [
            'stats' => [], 'applicationsByJob' => collect(), 'applicationsByMonth' => [],
        ])->renderSections()['content'];
        $this->assertStringContainsString('Aucune candidature pour le moment.', $empty);
    }
}
