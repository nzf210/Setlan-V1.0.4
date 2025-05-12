<?php

namespace App\Policies;

use App\Models\TahunModel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TahunPolicy
{
    use HandlesAuthorization;
    public function viewAny()
    {
        return true;
    }

    public function view()
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->hasRole(['super_admin' , 'admin_kab']);
    }

    public function update(User $user)
    {
        return $user->hasRole(['super_admin' , 'admin_kab']);
    }

    public function delete(User $user)
    {
        return $user->hasRole('super_admin');
    }
}
