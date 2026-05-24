<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Animal;
use App\Models\Breed;

class AnimalSeeder extends Seeder
{
    public function run(): void
    {
        $animals = [
            // Chickens
            ['tag_number' => 'CHK-001', 'name' => null,      'breed' => 'Ross 308',         'sex' => 'male',   'birthdate' => '2024-08-01', 'status' => 'active', 'weight' => 1.8],
            ['tag_number' => 'CHK-002', 'name' => null,      'breed' => 'Ross 308',         'sex' => 'female', 'birthdate' => '2024-08-01', 'status' => 'active', 'weight' => 1.6],
            ['tag_number' => 'CHK-003', 'name' => 'Mana',    'breed' => 'Lohmann Brown',    'sex' => 'female', 'birthdate' => '2023-11-15', 'status' => 'active', 'weight' => 1.9],
            ['tag_number' => 'CHK-004', 'name' => 'Petra',   'breed' => 'Lohmann Brown',    'sex' => 'female', 'birthdate' => '2023-11-15', 'status' => 'active', 'weight' => 2.0],
            ['tag_number' => 'CHK-005', 'name' => 'Tikoy',   'breed' => 'Darag',            'sex' => 'male',   'birthdate' => '2023-06-10', 'status' => 'active', 'weight' => 1.5],

            // Pigs
            ['tag_number' => 'PIG-001', 'name' => 'Lechon',  'breed' => 'Large White',      'sex' => 'male',   'birthdate' => '2024-01-20', 'status' => 'active', 'weight' => 65.0],
            ['tag_number' => 'PIG-002', 'name' => 'Baboy',   'breed' => 'Landrace',         'sex' => 'female', 'birthdate' => '2023-09-05', 'status' => 'active', 'weight' => 120.5],
            ['tag_number' => 'PIG-003', 'name' => 'Kiko',    'breed' => 'Duroc',            'sex' => 'male',   'birthdate' => '2023-07-12', 'status' => 'active', 'weight' => 145.0],
            ['tag_number' => 'PIG-004', 'name' => null,      'breed' => 'Philippine Native', 'sex' => 'female', 'birthdate' => '2024-03-01', 'status' => 'active', 'weight' => 38.0],

            // Cattle
            ['tag_number' => 'CTL-001', 'name' => 'Baka',    'breed' => 'Brahman',          'sex' => 'female', 'birthdate' => '2021-05-18', 'status' => 'active', 'weight' => 380.0],
            ['tag_number' => 'CTL-002', 'name' => 'Gatas',   'breed' => 'Holstein Friesian', 'sex' => 'female', 'birthdate' => '2020-03-22', 'status' => 'active', 'weight' => 520.0],
            ['tag_number' => 'CTL-003', 'name' => 'Toro',    'breed' => 'Brahman',          'sex' => 'male',   'birthdate' => '2019-11-30', 'status' => 'active', 'weight' => 610.0],

            // Carabao
            ['tag_number' => 'CBR-001', 'name' => 'Kalabaw', 'breed' => 'Carabao',          'sex' => 'male',   'birthdate' => '2018-07-04', 'status' => 'active', 'weight' => 480.0],
        ];

        foreach ($animals as $data) {
            $breed = Breed::where('name', $data['breed'])->first();
            Animal::firstOrCreate(['tag_number' => $data['tag_number']], [
                'tag_number' => $data['tag_number'],
                'name'       => $data['name'],
                'breed_id'   => $breed->id,
                'sex'        => $data['sex'],
                'birthdate'  => $data['birthdate'],
                'status'     => $data['status'],
                'weight'     => $data['weight'],
            ]);
        }
    }
}