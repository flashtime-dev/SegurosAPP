<?php

namespace App\Http\Controllers;

use App\Models\Comunidad;
use App\Models\Poliza;
use App\Models\Siniestro;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Obtener el usuario autenticado

        // Verificar si el usuario tiene el rol de administrador
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


        return Inertia::render('dashboard', [
            'comunidades' => $comunidades,
            'polizas' => $polizas,
            'siniestros' => $siniestros,
        ]);
    }
}
