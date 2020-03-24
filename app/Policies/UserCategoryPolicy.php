<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserCategoryPolicy
{

    use HandlesAuthorization;

    /**
     * Determine whether the user can update the resource.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserCategory  $userCategory
     * @return mixed
     */
    public function updateUserCategory(User $user, UserCategory $userCategory)
    {
        return $user->isOwner() && $userCategory->id == $user->id;
    }

    /**
     * Determine whether the user can permanently delete.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserCategory  $userCategory
     * @return mixed
     */
    public function deleteUserCategory(User $user, UserCategory $userCategory)
    {
        return $user->isOwner() && $userCategory->id == $user->id;
    }
}
