<?php

use App\Models\Goal;
use App\Models\User;
use App\Models\GoalCategory;
use Illuminate\Database\Seeder;

class GoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $goalCategories = GoalCategory::take(5)->get();

        User::all()->each(function($user) use ($goalCategories){
            factory(Goal::class, 2)->create([
                'user_id' => $user['id'],
                'goal_categories_id' => $goalCategories[rand(1,4)]['id']
            ]);
        });
    }
}
