<?php

namespace App\Policies;

use App\Models\KodeBarang;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class KodeBarangPolicy
{
    /**
     * Determine whether the user can view any models.
     */
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
        return in_array($user->roles[0]->name, ['admin_kab', 'super_admin']);
    }

    public function update(User $user)
    {
        return in_array($user->roles[0]->name, ['admin_kab', 'super_admin']);
    }

    public function delete(User $user)
    {
        return $user->roles[0]->name === 'super_admin';
    }
}
