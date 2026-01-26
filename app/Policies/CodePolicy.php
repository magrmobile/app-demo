<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Code;
use Illuminate\Auth\Access\HandlesAuthorization;

class CodePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Code');
    }

    public function view(AuthUser $authUser, Code $code): bool
    {
        return $authUser->can('View:Code');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Code');
    }

    public function update(AuthUser $authUser, Code $code): bool
    {
        return $authUser->can('Update:Code');
    }

    public function delete(AuthUser $authUser, Code $code): bool
    {
        return $authUser->can('Delete:Code');
    }

    public function restore(AuthUser $authUser, Code $code): bool
    {
        return $authUser->can('Restore:Code');
    }

    public function forceDelete(AuthUser $authUser, Code $code): bool
    {
        return $authUser->can('ForceDelete:Code');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Code');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Code');
    }

    public function replicate(AuthUser $authUser, Code $code): bool
    {
        return $authUser->can('Replicate:Code');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Code');
    }

}