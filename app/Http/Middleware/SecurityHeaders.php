<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Protection contre le sniffing de type MIME
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Protection contre le clickjacking : le site ne peut être encadré que
        // par lui-même (l'aperçu des PDF dans l'administration reste possible).
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Content-Security-Policy', "frame-ancestors 'self'");

        // Une politique CSP complète (script-src, style-src...) n'est pas activée
        // ici : les pages chargent des scripts depuis plusieurs CDN et utilisent
        // des scripts en ligne. Elle doit être construite et testée page par page.

        // HTTPS obligatoire en production
        if (config('app.env') === 'production') {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Politique de référent
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Fonctions du navigateur non utilisées par le site
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        return $response;
    }
}
