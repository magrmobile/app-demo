<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Stop;
use Illuminate\Auth\Access\HandlesAuthorization;

class StopPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Stop');
    }

    public function view(AuthUser $authUser, Stop $stop): bool
    {
        return $authUser->can('View:Stop');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Stop');
    }

    public function update(AuthUser $authUser, Stop $stop): bool
    {
        return $authUser->can('Update:Stop');
    }

    public function delete(AuthUser $authUser, Stop $stop): bool
    {
        return $authUser->can('Delete:Stop');
    }

    public function restore(AuthUser $authUser, Stop $stop): bool
    {
        return $authUser->can('Restore:Stop');
    }

    public function forceDelete(AuthUser $authUser, Stop $stop): bool
    {
        return $authUser->can('ForceDelete:Stop');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Stop');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Stop');
    }

    public function replicate(AuthUser $authUser, Stop $stop): bool
    {
        return $authUser->can('Replicate:Stop');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Stop');
    }

}