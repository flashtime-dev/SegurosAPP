<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ChechPermiso
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $permiso): Response
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Si el usuario es superadmin, permitir acceso sin verificar permisos
        if ($user->rol && $user->rol->id === 1) {
            return $next($request);
        }
        
        // Verifica si el usuario tiene el permiso requerido
        if ($user->rol && $user->rol->permisos->contains('id', $permiso)) {
            return $next($request);
        }
        
        // Si no tiene permisos, redirigir a página de acceso denegado
        return redirect()->route('acceso-denegado');
    }
}
