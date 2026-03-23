<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('admin.login');
        }

        $attributes = $user->getAttributes();
        $hasAdminMarkers = array_key_exists('is_admin', $attributes)
            || array_key_exists('role', $attributes)
            || array_key_exists('type', $attributes);

        $isAdmin = (bool) data_get($user, 'is_admin')
            || in_array(data_get($user, 'role'), ['admin', 'super-admin'], true)
            || in_array(data_get($user, 'type'), ['admin', 'super-admin'], true);

        abort_if($hasAdminMarkers && ! $isAdmin, 403);

        return $next($request);
    }
}