<?php

namespace Tests\Feature;

use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Checks that need no database: who is turned away before any query runs,
 * and that fixed admin URLs are not swallowed by wildcard routes.
 */
class AccessControlTest extends TestCase
{
    #[DataProvider('protectedUrls')]
    public function test_guests_are_sent_to_login(string $url): void
    {
        $this->get($url)->assertRedirect(route('login'));
    }

    public static function protectedUrls(): array
    {
        return [
            'dashboard' => ['/admin'],
            'job applications export' => ['/admin/job-applications/export'],
            'newsletter export' => ['/admin/newsletter/export'],
            'rapports export' => ['/admin/rapports/export'],
            'users' => ['/admin/users'],
            'thumbnail tool' => ['/convert'],
        ];
    }

    public function test_every_admin_route_requires_login_and_admin_access(): void
    {
        foreach (app('router')->getRoutes() as $route) {
            if (! str_starts_with($route->uri(), 'admin')) {
                continue;
            }

            $middleware = $route->gatherMiddleware();
            $this->assertContains('auth', $middleware, $route->uri());

            if ($route->getName() !== 'admin.logout') {
                $this->assertContains('admin.access', $middleware, $route->uri());
            }
        }
    }

    public function test_registration_is_closed_by_default(): void
    {
        config(['app.registration_enabled' => false]);

        // Without exception handling, so the test does not depend on the
        // error page (which reads the menu from the database).
        $this->withoutExceptionHandling();
        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        $this->get('/register');
    }

    public function test_security_headers_are_sent(): void
    {
        $response = $this->get('/admin');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    #[DataProvider('fixedAdminUrls')]
    public function test_fixed_admin_urls_reach_their_own_action(string $url, string $routeName): void
    {
        $route = app('router')->getRoutes()->match(Request::create($url, 'GET'));

        $this->assertSame($routeName, $route->getName());
    }

    public static function fixedAdminUrls(): array
    {
        return [
            ['/admin/rapports/export', 'admin.rapports.export'],
            ['/admin/rapports/download-zip', 'admin.rapports.download-zip'],
            ['/admin/rapports/pending-moderation', 'admin.rapports.pending'],
            ['/admin/job-applications/export', 'admin.job-applications.export'],
            ['/admin/job-applications/statistics', 'admin.job-applications.statistics'],
            ['/admin/projets/pending-moderation', 'admin.projets.pending'],
            ['/admin/publication/pending-moderation', 'admin.publication.pending'],
            ['/admin/newsletter/export', 'admin.newsletter.export'],
        ];
    }

    public function test_sensitive_public_forms_are_rate_limited(): void
    {
        foreach (['login.post', 'password.email', 'site.contact.store', 'site.newsletter.subscribe', 'site.job.apply.submit'] as $name) {
            $middleware = implode(' ', app('router')->getRoutes()->getByName($name)->gatherMiddleware());
            $this->assertStringContainsString('throttle:', $middleware, $name);
        }
    }
}
