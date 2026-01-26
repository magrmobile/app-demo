<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Dte;
use Illuminate\Auth\Access\HandlesAuthorization;

class DtePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Dte');
    }

    public function view(AuthUser $authUser, Dte $dte): bool
    {
        return $authUser->can('View:Dte');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Dte');
    }

    public function update(AuthUser $authUser, Dte $dte): bool
    {
        return $authUser->can('Update:Dte');
    }

    public function delete(AuthUser $authUser, Dte $dte): bool
    {
        return $authUser->can('Delete:Dte');
    }

    public function restore(AuthUser $authUser, Dte $dte): bool
    {
        return $authUser->can('Restore:Dte');
    }

    public function forceDelete(AuthUser $authUser, Dte $dte): bool
    {
        return $authUser->can('ForceDelete:Dte');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Dte');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Dte');
    }

    public function replicate(AuthUser $authUser, Dte $dte): bool
    {
        return $authUser->can('Replicate:Dte');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Dte');
    }

}