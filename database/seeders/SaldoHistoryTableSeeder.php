<?php

namespace Database\Seeders;

use App\Models\SaldoHistory;
use Illuminate\Database\Seeder;

class SaldoHistoryTableSeeder extends Seeder
{
    public function run(): void
    {
        SaldoHistory::query()->delete();

        SaldoHistory::insert([
            [
                'user_id' => 1,
                'amount' => 500000,
                'type' => 'topup',
                'description' => 'Initial Topup',
                'reference_id' => 'ORDER-001',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'user_id' => 1,
                'amount' => -15000,
                'type' => 'payment',
                'description' => 'Parking Payment Slot A1',
                'reference_id' => 'TRX-101',
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'user_id' => 2,
                'amount' => 1000000,
                'type' => 'topup',
                'description' => 'Topup for Admin testing',
                'reference_id' => 'ORDER-002',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'user_id' => 4,
                'amount' => 2000000,
                'type' => 'topup',
                'description' => 'Initial Owner Topup',
                'reference_id' => 'ORDER-003',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
        ]);
    }
}
