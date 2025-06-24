<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Permiso;
use Illuminate\Support\Facades\Log;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CheckPermiso
{
    /**
     * Este middleware verifica si el usuario autenticado tiene uno de los permisos requeridos.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permisoNombres): Response
    {
        try{
            // Verifica si el usuario está autenticado
            $user = Auth::user();
            // Si no hay usuario autenticado, redirigir a la página de login
            if (!$user) {
                Log::warning('Intento de acceso sin autenticar.');
                return redirect()->route('login');
            }

            //dd($user->id_rol);
            // Si el usuario es superadmin, permitir acceso sin verificar permisos
            if ($user && $user->id_rol == 1) {
                return $next($request);
            }

            // Soporte para múltiples permisos separados por "|"
            $nombres = explode('|', $permisoNombres);

            foreach ($nombres as $nombre) {
                // Verifica si el permiso existe en la base de datos
                $permiso = Permiso::where('nombre', $nombre)->first();

                // Si uno de los permisos no existe, lanza error 500
                if (!$permiso) {
                    abort(403, "Permiso '{$nombre}' no está registrado en la base de datos.");
                }

                // Verifica si el usuario tiene el permiso requerido
                if ($permiso && $user->rol && $user->rol->permisos->contains('id', $permiso->id)) {
                    Log::info("Acceso concedido al usuario ID {$user->id} con permiso '{$nombre}' en ruta {$request->route()->getName()}");
                    return $next($request);
                }
            }
            Log::warning("Permiso denegado al usuario ID {$user->id} para la ruta {$request->route()->getName()} con permisos requeridos: {$permisoNombres}");
            // Si no tiene ninguno de los permisos, denegar acceso
            return abort(403, 'Permiso denegado para el acceso');
        } catch (Throwable $e) {
            if ($e instanceof HttpException) {
                throw $e;
            }
            Log::error('Excepción capturada en CheckPermiso: ' . $e->getMessage(), [
                'exception' => $e,
                'route' => $request->route()->getName(),
                'user_id' => Auth::id(),
            ]);
            abort(500, 'Error inesperado al verificar permisos.');
        }
    }
}
