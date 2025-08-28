<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class UsageRecordTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('usage_records')->insert([
            [
                'usage_timestamps' => now(),
                'user_id' => 3,
                'machinery_id' => 1,
                'equipment_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'usage_timestamps' => now(),
                'user_id' => 3,
                'machinery_id' => null,
                'equipment_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'usage_timestamps' => now()->subHours(2),
                'user_id' => 5,
                'machinery_id' => 2,
                'equipment_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'usage_timestamps' => now()->subHours(4),
                'user_id' => 2,
                'machinery_id' => null,
                'equipment_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'usage_timestamps' => now()->subDay(1),
                'user_id' => 5,
                'machinery_id' => 3,
                'equipment_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'usage_timestamps' => now()->subDay(2),
                'user_id' => 2,
                'machinery_id' => null,
                'equipment_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'usage_timestamps' => now()->subHours(6),
                'user_id' => 3,
                'machinery_id' => 4,
                'equipment_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'usage_timestamps' => now()->subHours(8),
                'user_id' => 5,
                'machinery_id' => null,
                'equipment_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}