<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Permiso;

class CheckPermiso
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permisoNombre): Response
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Si el usuario es superadmin, permitir acceso sin verificar permisos
        if ($user->rol && $user->rol->id === 1) {
            return $next($request);
        }
        
        // Buscar el permiso por nombre
        $permiso = Permiso::where('nombre', $permisoNombre)->first();
        
        if (!$permiso) {
            // Si el permiso no existe en la BD
            abort(500, 'Permiso no encontrado en el sistema');
        }
        
        // Verifica si el usuario tiene el permiso requerido
        if ($user->rol && $user->rol->permisos->contains('id', $permiso->id)) {
            return $next($request);
        }
        
        // Si no tiene permisos, redirigir a página de acceso denegado
        return abort(403, 'Permiso denegado para el acceso');
    }
}