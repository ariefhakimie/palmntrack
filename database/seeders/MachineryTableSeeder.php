<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class MachineryTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('machineries')->insert([
            [
                'name' => 'Tractor',
                'model' => 'John Deere 5050D',
                'reg_num' => 'TRX-1001',
                'status' => 'OPERATIONAL',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tractor',
                'model' => 'Kubota M9540',
                'reg_num' => 'TRX-1002',
                'status' => 'UNDER MAINTENANCE',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Excavator',
                'model' => 'Komatsu PC200-8',
                'reg_num' => 'EXC-2003',
                'status' => 'OPERATIONAL',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Backhoe Loader',
                'model' => 'JCB 3CX',
                'reg_num' => 'BHL-3004',
                'status' => 'OPERATIONAL',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pickup Truck',
                'model' => 'Toyota Hilux 2.8G',
                'reg_num' => 'PUK-5005',
                'status' => 'UNDER MAINTENANCE',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lorry Tanker',
                'model' => 'Hino FG8J',
                'reg_num' => 'LOR-6006',
                'status' => 'OPERATIONAL',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Skid Steer Loader',
                'model' => 'Bobcat S450',
                'reg_num' => 'SKD-7007',
                'status' => 'OUT OF SERVICE',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Motorized Wheelbarrow',
                'model' => 'Canycom BFP602',
                'reg_num' => 'MTW-8008',
                'status' => 'OPERATIONAL',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
