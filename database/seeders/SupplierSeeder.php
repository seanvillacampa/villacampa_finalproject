<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name'             => 'AgriVet Supply Davao',
                'contact_person'   => 'Juan Reyes',
                'email'            => 'sales@agrivetdavao.com',
                'phone'            => '0917-123-4567',
                'street_address'   => 'Km 7 Quirino Avenue',
                'barangay_address' => 'Bangkal',
                'city_address'     => 'Davao City',
                'province_address' => 'Davao del Sur',
            ],
            [
                'name'             => 'Feliciano Feeds & Trading',
                'contact_person'   => 'Maria Feliciano',
                'email'            => 'felicianofeeds@gmail.com',
                'phone'            => '0918-222-3344',
                'street_address'   => '45 National Highway',
                'barangay_address' => 'Ulas',
                'city_address'     => 'Davao City',
                'province_address' => 'Davao del Sur',
            ],
            [
                'name'             => 'B-MEG Davao Dealer',
                'contact_person'   => 'Ricardo Santos',
                'email'            => 'bmeg.davao@dealer.com',
                'phone'            => '0922-555-7788',
                'street_address'   => '12 Saavedra Street',
                'barangay_address' => 'Toril',
                'city_address'     => 'Davao City',
                'province_address' => 'Davao del Sur',
            ],
            [
                'name'             => 'Vetline Animal Health',
                'contact_person'   => 'Dr. Liza Gomez',
                'email'            => 'vetline@animalhealth.ph',
                'phone'            => '0999-001-2233',
                'street_address'   => '88 McArthur Highway',
                'barangay_address' => 'Matina',
                'city_address'     => 'Davao City',
                'province_address' => 'Davao del Sur',
            ],
        ];

        foreach ($suppliers as $s) {
            Supplier::firstOrCreate(['name' => $s['name']], $s);
        }
    }
}