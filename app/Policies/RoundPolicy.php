<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Round;
use Illuminate\Auth\Access\HandlesAuthorization;

class RoundPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Round');
    }

    public function view(AuthUser $authUser, Round $round): bool
    {
        return $authUser->can('View:Round');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Round');
    }

    public function update(AuthUser $authUser, Round $round): bool
    {
        return $authUser->can('Update:Round');
    }

    public function delete(AuthUser $authUser, Round $round): bool
    {
        return $authUser->can('Delete:Round');
    }

    public function restore(AuthUser $authUser, Round $round): bool
    {
        return $authUser->can('Restore:Round');
    }

    public function forceDelete(AuthUser $authUser, Round $round): bool
    {
        return $authUser->can('ForceDelete:Round');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Round');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Round');
    }

    public function replicate(AuthUser $authUser, Round $round): bool
    {
        return $authUser->can('Replicate:Round');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Round');
    }

}