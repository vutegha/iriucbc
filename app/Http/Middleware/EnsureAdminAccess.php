<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gate for the whole admin area: the account must be active and hold at
 * least one role or permission. A freshly registered account with nothing
 * assigned cannot reach any admin page.
 */
class EnsureAdminAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $hasAccess = $user->active
            && ($user->roles->isNotEmpty() || $user->getAllPermissions()->isNotEmpty());

        if (! $hasAccess) {
            abort(403, 'Votre compte n\'a pas accès à l\'administration.');
        }

        return $next($request);
    }
}
