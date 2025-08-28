<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class EquipmentTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('equipment')->insert([
            [
                'name' => 'Machete',
                'model' => 'Golok 125',
                'status' => 'OPERATIONAL',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Safety Helmet',
                'model' => 'MSA VGard',
                'status' => 'UNDER MAINTENANCE',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Safety Boots',
                'model' => 'Kings 805',
                'status' => 'OPERATIONAL',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Wheelbarrow',
                'model' => 'WB65',
                'status' => 'OUT OF SERVICE',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Harvesting Pole',
                'model' => 'Egrek A200',
                'status' => 'OPERATIONAL',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Harvesting Pole',
                'model' => 'Dodos X1',
                'status' => 'UNDER MAINTENANCE',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sprayer',
                'model' => 'Solo 425',
                'status' => 'OPERATIONAL',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gloves',
                'model' => 'GripPro N9',
                'status' => 'OPERATIONAL',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
