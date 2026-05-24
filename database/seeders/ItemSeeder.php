<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;

use App\Models\Category;
use App\Models\UnitOfMeasure;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // Chicken Feed
            ['name' => 'Broiler Starter Crumble',  'sku' => 'CF-001', 'category' => 'Chicken Feed',     'unit' => 'kg',   'reorder_level' => 50],
            ['name' => 'Broiler Finisher Pellet',   'sku' => 'CF-002', 'category' => 'Chicken Feed',     'unit' => 'kg',   'reorder_level' => 50],
            ['name' => 'Layer Mash',                'sku' => 'CF-003', 'category' => 'Chicken Feed',     'unit' => 'sack', 'reorder_level' => 10],
            ['name' => 'Corn Grits',                'sku' => 'CF-004', 'category' => 'Chicken Feed',     'unit' => 'sack', 'reorder_level' => 15],

            // Pig Feed
            ['name' => 'Piglet Starter Feed',       'sku' => 'PF-001', 'category' => 'Pig Feed',         'unit' => 'kg',   'reorder_level' => 30],
            ['name' => 'Grower Pig Feed',           'sku' => 'PF-002', 'category' => 'Pig Feed',         'unit' => 'sack', 'reorder_level' => 10],
            ['name' => 'Finisher Pig Feed',         'sku' => 'PF-003', 'category' => 'Pig Feed',         'unit' => 'sack', 'reorder_level' => 10],
            ['name' => 'Sow & Boar Feed',           'sku' => 'PF-004', 'category' => 'Pig Feed',         'unit' => 'sack', 'reorder_level' => 8],

            // Cattle Feed
            ['name' => 'Cattle Concentrate',        'sku' => 'CTF-001','category' => 'Cattle Feed',      'unit' => 'sack', 'reorder_level' => 5],
            ['name' => 'Rice Bran',                 'sku' => 'CTF-002','category' => 'Cattle Feed',      'unit' => 'sack', 'reorder_level' => 10],

            // Chicken Medicine
            ['name' => 'Newcastle Disease Vaccine', 'sku' => 'CM-001', 'category' => 'Chicken Medicine', 'unit' => 'vial', 'reorder_level' => 5],
            ['name' => 'Infectious Bronchitis Vaccine','sku'=>'CM-002','category' => 'Chicken Medicine', 'unit' => 'vial', 'reorder_level' => 5],
            ['name' => 'Amoxicillin 500mg',         'sku' => 'CM-003', 'category' => 'Chicken Medicine', 'unit' => 'tab',  'reorder_level' => 50],
            ['name' => 'Vitamins & Electrolytes',   'sku' => 'CM-004', 'category' => 'Chicken Medicine', 'unit' => 'g',    'reorder_level' => 100],

            // Pig Medicine
            ['name' => 'Ivermectin 1% Solution',    'sku' => 'PM-001', 'category' => 'Pig Medicine',     'unit' => 'mL',   'reorder_level' => 50],
            ['name' => 'Oxytetracycline 200mg/mL',  'sku' => 'PM-002', 'category' => 'Pig Medicine',     'unit' => 'mL',   'reorder_level' => 50],
            ['name' => 'Hog Cholera Vaccine',        'sku' => 'PM-003', 'category' => 'Pig Medicine',     'unit' => 'vial', 'reorder_level' => 5],
            ['name' => 'Albendazole 250mg',         'sku' => 'PM-004', 'category' => 'Pig Medicine',     'unit' => 'tab',  'reorder_level' => 30],

            // Cattle Medicine
            ['name' => 'FMD Vaccine',               'sku' => 'CTM-001','category' => 'Cattle Medicine',  'unit' => 'vial', 'reorder_level' => 5],
            ['name' => 'Ivermectin Injectable 50mL','sku' => 'CTM-002','category' => 'Cattle Medicine',  'unit' => 'btl',  'reorder_level' => 3],
            ['name' => 'Penicillin-Streptomycin',   'sku' => 'CTM-003','category' => 'Cattle Medicine',  'unit' => 'vial', 'reorder_level' => 5],

            // Supplements
            ['name' => 'B-Complex Vitamin',         'sku' => 'SUP-001','category' => 'Supplements',      'unit' => 'btl',  'reorder_level' => 5],
            ['name' => 'Calcium Supplement',        'sku' => 'SUP-002','category' => 'Supplements',      'unit' => 'kg',   'reorder_level' => 10],

            // Disinfectants
            ['name' => 'Virkon S Disinfectant',     'sku' => 'DIS-001','category' => 'Disinfectants',    'unit' => 'kg',   'reorder_level' => 2],
            ['name' => 'Glutaraldehyde Solution',   'sku' => 'DIS-002','category' => 'Disinfectants',    'unit' => 'L',    'reorder_level' => 5],
        ];

        foreach ($items as $data) {
            $category = Category::where('name', $data['category'])->first();
            $unit     = UnitOfMeasure::where('abbreviation', $data['unit'])->first();
            Item::firstOrCreate(['sku' => $data['sku']], [
                'name'          => $data['name'],
                'sku'           => $data['sku'],
                'category_id'   => $category->id,
                'unit_id'       => $unit->id,
                'reorder_level' => $data['reorder_level'],
                'description'   => null,
            ]);
        }
    }
}
