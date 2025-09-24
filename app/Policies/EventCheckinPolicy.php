<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\EventCheckin;
use App\Models\User;

class EventCheckinPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any EventCheckin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EventCheckin $eventcheckin): bool
    {
        return $user->checkPermissionTo('view EventCheckin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create EventCheckin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EventCheckin $eventcheckin): bool
    {
        return $user->checkPermissionTo('update EventCheckin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EventCheckin $eventcheckin): bool
    {
        return $user->checkPermissionTo('delete EventCheckin');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any EventCheckin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, EventCheckin $eventcheckin): bool
    {
        return $user->checkPermissionTo('restore EventCheckin');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any EventCheckin');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, EventCheckin $eventcheckin): bool
    {
        return $user->checkPermissionTo('replicate EventCheckin');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder EventCheckin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, EventCheckin $eventcheckin): bool
    {
        return $user->checkPermissionTo('force-delete EventCheckin');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any EventCheckin');
    }
}
