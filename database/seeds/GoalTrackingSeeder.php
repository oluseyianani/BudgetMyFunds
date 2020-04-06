<?php

use App\Models\Goal;
use App\Models\GoalTracking;
use Illuminate\Database\Seeder;

class GoalTrackingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Goal::all()->each(function($goal) {
            factory(GoalTracking::class, 5)->create([
                'goal_id' => $goal['id']
            ]);
        });
    }
}
