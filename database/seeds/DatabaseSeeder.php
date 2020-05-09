<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(SubCategoryTableSeeder::class);
        $this->call(UserProfileSeeder::class);
        $this->call(GoalCategorySeeder::class);
        $this->call(GoalSeeder::class);
        $this->call(GoalTrackingSeeder::class);
        $this->call(UserCategoryTableSeeder::class);
        $this->call(BudgetTableSeeder::class);
    }
}
