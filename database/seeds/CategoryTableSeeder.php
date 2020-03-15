<?php

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function($user) {
            for($i = 0; $i < 5; $i++) {
                $user->category()->create(array_merge(factory(Category::class)->make()->toArray(), ['creator' => $user->id]));
            }
        });
    }
}
