<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CommodityTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('commodities')->insert([
            [
                'name' => 'FFB Batch A',
                'type' => 'Fresh Fruit Bunches (FFB)',
                'quantity' => 25.5,
                'metric' => 'mt',
                'supplier' => 'Nursery A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'EFB Batch A',
                'type' => 'Empty Fruit Bunches (EFB)',
                'quantity' => 12.0,
                'metric' => 'mt',
                'supplier' => 'Nursery B',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'GrowMax',
                'type' => 'Fertilizer',
                'quantity' => 100,
                'metric' => 'kg',
                'supplier' => 'Agro Supply Co.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'WeedBlaster',
                'type' => 'Herbicide',
                'quantity' => 200,
                'metric' => 'liters',
                'supplier' => 'Green Solutions Ltd.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'FungiShield',
                'type' => 'Fungicide',
                'quantity' => 150,
                'metric' => 'kg',
                'supplier' => 'CropSafe Inc.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PestGuard',
                'type' => 'Pesticide',
                'quantity' => 300,
                'metric' => 'liters',
                'supplier' => 'BioProtect Co.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'GrowEasy',
                'type' => 'Growth Regulator',
                'quantity' => 50,
                'metric' => 'liters',
                'supplier' => 'AgriTech Solutions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SoilVitalize',
                'type' => 'Soil Conditioner',
                'quantity' => 400,
                'metric' => 'kg',
                'supplier' => 'EarthCare Ltd.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
