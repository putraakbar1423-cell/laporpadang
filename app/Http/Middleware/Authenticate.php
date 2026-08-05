<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * This is a pure API backend (no web routes), so we always return null to
     * produce a JSON 401 response instead of redirecting to a non-existent
     * `login` route (which would throw RouteNotFoundException).
     */
    protected function redirectTo(Request $request): ?string
    {
        return null;
    }
}
