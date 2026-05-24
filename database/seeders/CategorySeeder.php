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
        $categories = [
            ['name' => 'Chicken Feed',      'description' => 'Feeds for poultry/chicken'],
            ['name' => 'Pig Feed',          'description' => 'Feeds for swine/pig'],
            ['name' => 'Cattle Feed',       'description' => 'Feeds for cattle and carabao'],
            ['name' => 'Chicken Medicine',  'description' => 'Vaccines and medicines for poultry'],
            ['name' => 'Pig Medicine',      'description' => 'Medicines and dewormers for pigs'],
            ['name' => 'Cattle Medicine',   'description' => 'Medicines and vaccines for cattle'],
            ['name' => 'Supplements',       'description' => 'Vitamins and mineral supplements'],
            ['name' => 'Equipment',         'description' => 'Farm tools and equipment'],
            ['name' => 'Disinfectants',     'description' => 'Cleaning and biosecurity supplies'],
        ];
        foreach ($categories as $c) Category::firstOrCreate(['name' => $c['name']], $c);
    }
}
