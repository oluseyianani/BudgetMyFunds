<?php

use App\Models\User;
use App\Models\Category;
use App\Models\UserCategory;
use Illuminate\Database\Seeder;

class UserCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = Category::take(3)->get();
        User::all()->each(function($user) use ($category) {
            factory(UserCategory::class, 2)->create([
                'user_id' => $user['id'],
                'category_id' => $category[rand(1,2)]['id']
            ]);
        });
    }
}
