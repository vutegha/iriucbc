<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Public self-registration is off unless REGISTRATION_ENABLED=true.
 * Accounts are normally created by an administrator.
 */
class EnsureRegistrationEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(config('app.registration_enabled'), 404);

        return $next($request);
    }
}
