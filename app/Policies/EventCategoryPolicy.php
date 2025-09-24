<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\EventCategory;
use App\Models\User;

class EventCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any EventCategory');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EventCategory $eventcategory): bool
    {
        return $user->checkPermissionTo('view EventCategory');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create EventCategory');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EventCategory $eventcategory): bool
    {
        return $user->checkPermissionTo('update EventCategory');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EventCategory $eventcategory): bool
    {
        return $user->checkPermissionTo('delete EventCategory');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any EventCategory');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, EventCategory $eventcategory): bool
    {
        return $user->checkPermissionTo('restore EventCategory');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any EventCategory');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, EventCategory $eventcategory): bool
    {
        return $user->checkPermissionTo('replicate EventCategory');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder EventCategory');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, EventCategory $eventcategory): bool
    {
        return $user->checkPermissionTo('force-delete EventCategory');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any EventCategory');
    }
}
