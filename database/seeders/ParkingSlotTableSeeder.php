<?php

namespace Database\Seeders;

use App\Models\ParkingMapSlot;
use Illuminate\Database\Seeder;

class ParkingSlotTableSeeder extends Seeder
{
    public function run(): void
    {
        ParkingMapSlot::query()->delete();

        // Slots for Floor 1 (parking_map_id: 1)
        $slots = [];
        
        // Rows A and B
        for ($i = 1; $i <= 5; $i++) {
            $slots[] = [
                'parking_map_id' => 1,
                'code' => 'A' . $i,
                'x' => 100 + (($i - 1) * 150),
                'y' => 150,
                'width' => 100,
                'height' => 180,
                'area_parkir_id' => 1,
                'camera_id' => null,
                'notes' => 'Slot VIP',
                'meta' => json_encode(['orientation' => 'vertical']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        for ($i = 1; $i <= 5; $i++) {
            $slots[] = [
                'parking_map_id' => 1,
                'code' => 'B' . $i,
                'x' => 100 + (($i - 1) * 150),
                'y' => 450,
                'width' => 100,
                'height' => 180,
                'area_parkir_id' => 1,
                'camera_id' => null,
                'notes' => 'Slot Regular',
                'meta' => json_encode(['orientation' => 'vertical']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Some slots for Floor 2 (parking_map_id: 2)
        for ($i = 1; $i <= 5; $i++) {
            $slots[] = [
                'parking_map_id' => 2,
                'code' => 'C' . $i,
                'x' => 100 + (($i - 1) * 150),
                'y' => 150,
                'width' => 100,
                'height' => 180,
                'area_parkir_id' => 2,
                'camera_id' => null,
                'notes' => 'Slot Lantai 2',
                'meta' => json_encode(['orientation' => 'vertical']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        ParkingMapSlot::insert($slots);
    }
}
