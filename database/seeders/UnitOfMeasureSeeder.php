<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnitOfMeasure;

class UnitOfMeasureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
    {
        $units = [
            ['name' => 'Kilogram',    'abbreviation' => 'kg'],
            ['name' => 'Gram',        'abbreviation' => 'g'],
            ['name' => 'Liter',       'abbreviation' => 'L'],
            ['name' => 'Milliliter',  'abbreviation' => 'mL'],
            ['name' => 'Sack',        'abbreviation' => 'sack'],
            ['name' => 'Bag',         'abbreviation' => 'bag'],
            ['name' => 'Bottle',      'abbreviation' => 'btl'],
            ['name' => 'Vial',        'abbreviation' => 'vial'],
            ['name' => 'Tablet',      'abbreviation' => 'tab'],
            ['name' => 'Piece',       'abbreviation' => 'pc'],
        ];
        foreach ($units as $u) UnitOfMeasure::firstOrCreate(['abbreviation' => $u['abbreviation']], $u);
    }
}
