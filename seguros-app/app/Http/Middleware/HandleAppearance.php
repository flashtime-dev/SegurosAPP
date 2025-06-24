<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Throwable;

class HandleAppearance
{
    /**
     * Este middleware maneja la apariencia de la aplicación según la cookie 'appearance'.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try{
            //View::share permite compartir datos con todas las vistas
            View::share('appearance', $request->cookie('appearance') ?? 'system');
            return $next($request);
        } catch (Throwable $e) {
            Log::error('Error en middleware HandleAppearance: ' . $e->getMessage(), [
                'exception' => $e,
                'route' => $request->route()->getName(),
                'user_id' => Auth::id(),
            ]);
            abort(500, 'Error inesperado al manejar apariencia.');
        }
    }
}
