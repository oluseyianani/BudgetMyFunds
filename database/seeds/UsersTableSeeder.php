<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 50)->create();

        User::all()->each(function ($user) {
            $role = Role::inRandomOrder()->limit(1)->get('id');
            $user->roles()->attach($role[0]['id'], ['approved' => 1]);
        });
    }
}
