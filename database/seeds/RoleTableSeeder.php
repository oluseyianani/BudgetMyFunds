<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['Owner', 'Collaborator', 'Admin', 'Super admin'];

        foreach($roles as &$role) {
            Role::create([
                'role' => $role
            ]);
        }
    }
}
