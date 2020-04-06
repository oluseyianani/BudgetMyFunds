<?php

namespace App\Policies;

use App\Models\Goal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GoalPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the goal.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Goal  $goal
     * @return mixed
     */
    public function view(User $user, Goal $goal)
    {
        return $user->isSuperAdmin() || $user->id === $goal->user_id;
    }

    /**
     * Determine whether the user can update the goal.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Goal  $goal
     * @return mixed
     */
    public function update(User $user, Goal $goal)
    {
        return $user->id === $goal->user_id;
    }

       /**
     * Determine whether the user can create the goal tracking.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Goal  $goal
     * @return mixed
     */
    public function create(User $user, Goal $goal)
    {
        return $user->id === $goal->user_id;
    }

    /**
     * Determine whether the user can delete the goal.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Goal  $goal
     * @return mixed
     */
    public function delete(User $user, Goal $goal)
    {
        return $user->isSuperAdmin() || $user->id === $goal->user_id;
    }
}
