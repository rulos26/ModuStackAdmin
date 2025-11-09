<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view roles');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): bool
    {
        return $user->can('view roles');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create roles');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): bool
    {
        // Prevenir modificación de roles del sistema
        if (in_array($role->name, ['SuperAdmin', 'super-admin'])) {
            return $user->hasRole('SuperAdmin') && $user->can('update roles');
        }
        return $user->can('update roles');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): bool
    {
        // Prevenir eliminación de roles del sistema
        if (in_array($role->name, ['SuperAdmin', 'super-admin', 'Admin', 'admin'])) {
            return false;
        }
        return $user->can('delete roles');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Role $role): bool
    {
        return $user->can('restore roles');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        // Prevenir eliminación permanente de roles del sistema
        if (in_array($role->name, ['SuperAdmin', 'super-admin', 'Admin', 'admin'])) {
            return false;
        }
        return $user->can('force delete roles');
    }
}
