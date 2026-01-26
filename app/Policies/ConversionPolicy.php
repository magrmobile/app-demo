<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Conversion;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConversionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Conversion');
    }

    public function view(AuthUser $authUser, Conversion $conversion): bool
    {
        return $authUser->can('View:Conversion');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Conversion');
    }

    public function update(AuthUser $authUser, Conversion $conversion): bool
    {
        return $authUser->can('Update:Conversion');
    }

    public function delete(AuthUser $authUser, Conversion $conversion): bool
    {
        return $authUser->can('Delete:Conversion');
    }

    public function restore(AuthUser $authUser, Conversion $conversion): bool
    {
        return $authUser->can('Restore:Conversion');
    }

    public function forceDelete(AuthUser $authUser, Conversion $conversion): bool
    {
        return $authUser->can('ForceDelete:Conversion');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Conversion');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Conversion');
    }

    public function replicate(AuthUser $authUser, Conversion $conversion): bool
    {
        return $authUser->can('Replicate:Conversion');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Conversion');
    }

}