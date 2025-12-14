<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizationMember
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $organization = $request->route('organization');

        if (!$organization) {
            return $next($request);
        }

        $user = auth()->user();

        // Admin može sve
        if ($user->is_admin) {
            return $next($request);
        }

        // Proveri da li je član
        if (!$organization->isMember($user)) {
            abort(403, 'Nemate pristup ovoj organizaciji.');
        }

        return $next($request);
    }
}
