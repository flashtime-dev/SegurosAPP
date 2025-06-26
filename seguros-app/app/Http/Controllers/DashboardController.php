<?php

namespace App\Http\Controllers;

use App\Models\Comunidad;
use App\Models\Poliza;
use App\Models\Siniestro;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

// Controlador para manejar el dashboard de la aplicación
class DashboardController extends Controller
{
    public function index()
    {
        // Segun el rol del usuario, se obtienen diferentes datos para el dashboard
        try{
            $user = Auth::user(); // Obtener el usuario autenticado
            
            $userLogued = User::with('rol.permisos')->findOrFail($user->id);

            //dd($userLogued->rol);
            // Verificar si el usuario tiene el rol de superadministrador
            if ($user->rol->nombre == 'Superadministrador') {
                $comunidades = Comunidad::all()->count();
                $polizas = Poliza::where('estado', 'En Vigor')->count();
                $siniestros = Siniestro::where('estado', 'Abierto')->count();
            } else {
                // Obtener comunidades donde el usuario es propietario O está asignado como usuario
                $comunidades = Comunidad::where('id_propietario', $user->id)
                    ->orWhereHas('users', function ($query) use ($user) {
                        $query->where('users.id', $user->id);
                    })
                    ->get();
                // Contar el número de pólizas y siniestros
                $polizas = Poliza::whereIn('id_comunidad', $comunidades->pluck('id'))
                    ->where('estado', 'En Vigor')
                    ->get();
                $siniestros = Siniestro::whereIn('id_poliza', $polizas->pluck('id'))
                    ->where('estado', 'Abierto')
                    ->count();
                $comunidades = $comunidades->count(); // Contar el número de comunidades
                $polizas = $polizas->count(); // Contar el número de pólizas
            }

            Log::info('✅ Dashboard cargado correctamente.', [
                'user_id' => $user->id,
                'comunidades' => $comunidades,
                'polizas' => $polizas,
                'siniestros' => $siniestros,
            ]);

            return Inertia::render('dashboard', [
                'comunidades' => $comunidades,
                'polizas' => $polizas,
                'siniestros' => $siniestros,
                'rol' => $userLogued->rol,
            ]);
        } catch (Throwable $e) {
            Log::error('❌ Error al cargar el dashboard', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with([
                'error' => [
                    'id' => uniqid(),
                    'mensaje' => "Error al cargar el dashboard",
                ],
            ]);
        }
    }
}
