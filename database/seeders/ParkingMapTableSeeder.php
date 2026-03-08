<?php

namespace Database\Seeders;

use App\Models\ParkingMap;
use Illuminate\Database\Seeder;

class ParkingMapTableSeeder extends Seeder
{
    public function run(): void
    {
        ParkingMap::query()->delete();

        ParkingMap::insert([
            [
                'id' => 1,
                'area_parkir_id' => 1,
                'name' => 'Lantai 1',
                'code' => 'floor1',
                'image_path' => 'images/floor1.png',
                'width' => 1200,
                'height' => 800,
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'area_parkir_id' => 2,
                'name' => 'Lantai 2',
                'code' => 'floor2',
                'image_path' => 'images/floor1.png', // Reusing the same image for simplicity in demo
                'width' => 1200,
                'height' => 800,
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
