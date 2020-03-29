<?php

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class UserProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all()->each(function($user) {
            $user->userProfile()->create(factory(UserProfile::class)->make()->toArray());
        });
    }
}
