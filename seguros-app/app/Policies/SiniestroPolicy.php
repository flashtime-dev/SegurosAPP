<?php

namespace App\Policies;

use App\Models\Siniestro;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SiniestroPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Siniestro $siniestro): bool
    {
        // Superadmin
        if ($user->id_rol === 1) {
            return true;
        }

        // Si la póliza pertenece a una comunidad de la que eres propietario
        return $siniestro->poliza->comunidad->id_propietario === $user->id
            // o si estás asignado como usuario en esa comunidad…
            || $siniestro->poliza->comunidad->users->contains('id', $user->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Siniestro $siniestro): bool
    {
        // Superadmin
        if ($user->id_rol === 1) {
            return true;
        }

        // Si la póliza pertenece a una comunidad de la que eres propietario
        return $siniestro->poliza->comunidad->id_propietario === $user->id
            // o si estás asignado como usuario en esa comunidad…
            || $siniestro->poliza->comunidad->users->contains('id', $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Siniestro $siniestro): bool
    {
        // Superadmin
        if ($user->id_rol === 1) {
            return true;
        }

        // Si la póliza pertenece a una comunidad de la que eres propietario
        return $siniestro->poliza->comunidad->id_propietario === $user->id
            // o si estás asignado como usuario en esa comunidad…
            || $siniestro->poliza->comunidad->users->contains('id', $user->id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Siniestro $siniestro): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Siniestro $siniestro): bool
    {
        return false;
    }
}
