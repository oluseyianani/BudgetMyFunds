<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;
use App\Models\Budget;
use App\Models\Category;
use App\Models\SubCategory;

trait SetupHelper 
{
    /**
     * Inserts All Roles
     *
     * @return Collection
     */
    public function insertRoles()
    {
        $roles = ['Owner', 'Collaborator', 'Admin', 'Super admin'];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'role' => $role
            ]);
        }

        return Role::all();
    }

    /**
     * Inserts a single role
     *
     * @param string $role
     * @return App\Model\Role
     */
    public function insertSingleRole(string $role)
    {
        $role = Role::firstOrCreate([
            'role' => $role
        ]);

        return $role;
    }

    /**
     * Inserts User to the database
     *
     * @param integer $number
     * @return App\Model\User
     */
    public function insertUser(int $number = 1)
    {
        return factory(User::class, $number)->create();
    }

    /**
     * Makes User object without inserting
     *
     * @param integer $number
     * @return App\Model\User
     */
    public function makeUser(int $number = 1)
    {
        return factory(User::class, $number)->make();
    }

    /**
     * Creats a user with role
     *
     * @param string $role
     * @return App\Model\User
     */
    public function createUserWithRole(string $role)
    {
        $role = $this->insertSingleRole($role)->toArray();
       
        $user = User::firstOrCreate([
            'email' => 'test@test.com',
            'password' => 'password123',
            'email_verified_at' => now()
        ]);

        $user->roles()->attach($role['id'], ['approved' => 1]);

        return $user;
    }

    public function createCategory(int $userId)
    {
        return factory(Category::class)->create([
            'creator' => $userId
        ]);
    }

    public function insertCategoryAndSubcategory(int $userId)
    {
        $category = $this->createCategory($userId)->toArray();

        $subCategory =  factory(SubCategory::class)->create([
            'category_id' => $category['id'],
        ])->toArray();
        
        return [
            'category' => $category,
            'sub' => $subCategory
        ];
    }

    public function insertbudgetdata(int $userId)
    {
        $data = $this->insertCategoryAndSubcategory($userId);
        
        return factory(Budget::class)->create([
            'category_id' => $data['category']['id'],
            'sub_category_id' => $data['sub']['id'],
            'user_id' => $userId,
            'budget_for_month' => '2019-06-01'
        ])->toArray();
    }
}

