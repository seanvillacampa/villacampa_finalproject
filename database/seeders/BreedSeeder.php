<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Breed;
use App\Models\Animal;

class BreedSeeder extends Seeder
{
    public function run(): void
    {
        $breeds = [
            ['name' => 'Ross 308',          'species' => 'Chicken', 'description' => 'Commercial broiler breed'],
            ['name' => 'Lohmann Brown',     'species' => 'Chicken', 'description' => 'High-production layer hen'],
            ['name' => 'Darag',             'species' => 'Chicken', 'description' => 'Native Filipino free-range chicken'],
            ['name' => 'Landrace',          'species' => 'Pig',     'description' => 'Lean meat commercial pig breed'],
            ['name' => 'Large White',       'species' => 'Pig',     'description' => 'Fast-growing commercial pig'],
            ['name' => 'Duroc',             'species' => 'Pig',     'description' => 'Hardy pig known for good marbling'],
            ['name' => 'Philippine Native', 'species' => 'Pig',     'description' => 'Native backyard pig breed'],
            ['name' => 'Brahman',           'species' => 'Cattle',  'description' => 'Heat-tolerant beef cattle'],
            ['name' => 'Holstein Friesian', 'species' => 'Cattle',  'description' => 'High-yield dairy cattle'],
            ['name' => 'Carabao',           'species' => 'Carabao', 'description' => 'Philippine native water buffalo'],
        ];
        foreach ($breeds as $b) Breed::firstOrCreate(['name' => $b['name']], $b);
    }
}
