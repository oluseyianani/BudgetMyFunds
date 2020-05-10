<?php

use App\Models\User;
use App\Models\Budget;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class BudgetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            $randomCategory = Category::inRandomOrder()->limit(1)->get('id');
            $randomSubCategory = SubCategory::inRandomOrder()->limit(1)->get('id');

            User::all()->each(function ($user) use ($randomCategory, $randomSubCategory) {
                factory(Budget::class)->create([
                    'user_id' => $user['id'],
                    'category_id' => $randomCategory[0]['id'],
                    'sub_category_id' => $randomSubCategory[0]['id']
                ]);
            });
        }
    }
}
