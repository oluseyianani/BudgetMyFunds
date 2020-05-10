<?php

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class SubCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::all()->each(function ($category) {
            for ($i = 0; $i <2; $i++) {
                $category->subcategory()->create(factory(SubCategory::class)->make()->toArray());
            }
        });
    }
}
