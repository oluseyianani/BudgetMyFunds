<?php

use App\Models\GoalCategory;
use Illuminate\Database\Seeder;

class GoalCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(GoalCategory::class, 7)->create();
    }
}
