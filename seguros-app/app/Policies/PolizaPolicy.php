<?php

namespace App\Policies;

use App\Models\Poliza;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PolizaPolicy
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
    public function view(User $user, Poliza $poliza): bool
    {
        // Superadmin
        if ($user->id_rol === 1) {
            return true;
        }

        // Si la póliza pertenece a una comunidad de la que eres propietario
        return $poliza->comunidad->id_propietario === $user->id
            // o si estás asignado como usuario en esa comunidad…
            || $poliza->comunidad->users->contains('id', $user->id);
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
    public function update(User $user, Poliza $poliza): bool
    {
        // Superadmin
        if ($user->id_rol === 1) {
            return true;
        }

        // Si la póliza pertenece a una comunidad de la que eres propietario
        return $poliza->comunidad->id_propietario === $user->id
            // o si estás asignado como usuario en esa comunidad…
            || $poliza->comunidad->users->contains('id', $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Poliza $poliza): bool
    {
        // Superadmin
        if ($user->id_rol === 1) {
            return true;
        }

        // Si la póliza pertenece a una comunidad de la que eres propietario
        return $poliza->comunidad->id_propietario === $user->id
            // o si estás asignado como usuario en esa comunidad…
            || $poliza->comunidad->users->contains('id', $user->id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Poliza $poliza): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Poliza $poliza): bool
    {
        return false;
    }
}
