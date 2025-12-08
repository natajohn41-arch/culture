<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $user = auth()->user();
        
        // Allow admins to bypass role checks (admin has full access)
        $userRoleName = $user->role->nom_role ?? null;
        if ($userRoleName && strtolower($userRoleName) === 'admin') {
            return $next($request);
        }

        // Vérifier si l'utilisateur a le rôle requis
        if (!$user->role || $user->role->nom_role !== $role) {
            abort(403, 'Accès non autorisé. Rôle requis: ' . $role);
        }

        return $next($request);
    }
}