<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*collect([
            'Electronics',
            'Books',
            'Clothing',
            'Home & Kitchen',
        ])->each(fn ($category) => Category::create(['name' => $category]));*/
        collect([
            'Electronics',
            'Books',
            'Clothing',
            'Home & Kitchen',
        ])->each(function($category){
            Category::firstOrCreate(['name' => $category]);
        });
    }
}
