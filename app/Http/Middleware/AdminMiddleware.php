<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
public function handle(Request $request, Closure $next): Response
{
    // vérifie deux conditions : 
        // 1. l'utilisateur est connecté (auth()->check())
        // 2. l'utilisateur a is_admin = true dans la base 
        if (!auth()->check() || !auth()->user()->is_admin) {
            // Si l'une des conditions échoue, on redirige vers la page de connexion 
            // L'utilisateur ne voit jamais le dashboard s'il n'est pas admin
            return redirect()->route('admin.login');
        }
        // Tout est bon, on laisse passer la requête 
        return $next($request);
}
}