<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Permiso;
use Illuminate\Support\Facades\Log;
use Throwable;

class CheckPermiso
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permisoNombres): Response
    {
        try{
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login');
            }

            // Si el usuario es superadmin, permitir acceso sin verificar permisos
            if ($user->rol && $user->rol->id === 1) {
                return $next($request);
            }

            // Soporte para múltiples permisos separados por "|"
            $nombres = explode('|', $permisoNombres);

            foreach ($nombres as $nombre) {
                $permiso = Permiso::where('nombre', $nombre)->first();

                if (!$permiso) {
                    // Si uno de los permisos no existe, lanza error 500
                    abort(500, "Permiso '{$nombre}' no está registrado en la base de datos.");
                }

                if ($permiso && $user->rol && $user->rol->permisos->contains('id', $permiso->id)) {
                    return $next($request);
                }
            }

            // Si no tiene ninguno de los permisos, denegar acceso
            return abort(403, 'Permiso denegado para el acceso');
        } catch (Throwable $e) {
            Log::error('Error en middleware CheckPermiso: ' . $e->getMessage(), [
                'exception' => $e,
                'route' => $request->route()->getName(),
                'user_id' => Auth::id(),
            ]);
            abort(500, 'Error inesperado al verificar permisos.');
        }
    }
}
