<?php

namespace Database\Seeders;

use App\Models\Transaksi;
use Illuminate\Database\Seeder;

class TransaksiTableSeeder extends Seeder
{
    public function run(): void
    {
        Transaksi::query()->delete();

        // Insert awal tanpa relasi ke pembayaran untuk menghindari masalah FK
        Transaksi::insert([
            [
                'id_parkir' => 1,
                'id_kendaraan' => 1,
                'waktu_masuk' => now()->subDays(2)->format('Y-m-d H:i:s'),
                'waktu_keluar' => now()->subDay()->format('Y-m-d H:i:s'),
                'id_tarif' => 2,
                'durasi_jam' => 24,
                'biaya_total' => 120000,
                'status' => 'keluar',
                'bookmarked_at' => null,
                'catatan' => null,
                'status_pembayaran' => 'berhasil',
                'id_pembayaran' => null,
                'midtrans_order_id' => 'TRX-101',
                'id_user' => 1,
                'id_area' => 1,
                'parking_map_slot_id' => 1, // A1
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDay(),
                'deleted_at' => null,
            ],
            [
                'id_parkir' => 2,
                'id_kendaraan' => 2,
                'waktu_masuk' => now()->subHours(5)->format('Y-m-d H:i:s'),
                'waktu_keluar' => null,
                'id_tarif' => 1,
                'durasi_jam' => 0,
                'biaya_total' => 0,
                'status' => 'masuk',
                'bookmarked_at' => null,
                'catatan' => null,
                'status_pembayaran' => 'pending',
                'id_pembayaran' => null,
                'midtrans_order_id' => null,
                'id_user' => 1,
                'id_area' => 1,
                'parking_map_slot_id' => 2, // A2
                'created_at' => now()->subHours(5),
                'updated_at' => now()->subHours(5),
                'deleted_at' => null,
            ],
            [
                'id_parkir' => 3,
                'id_kendaraan' => 3,
                'waktu_masuk' => now()->subHours(2)->format('Y-m-d H:i:s'),
                'waktu_keluar' => null,
                'id_tarif' => 1,
                'durasi_jam' => 0,
                'biaya_total' => 0,
                'status' => 'masuk',
                'bookmarked_at' => now()->subHours(2),
                'catatan' => 'Booking via App',
                'status_pembayaran' => 'pending',
                'id_pembayaran' => null,
                'midtrans_order_id' => null,
                'id_user' => 1,
                'id_area' => 1,
                'parking_map_slot_id' => 3, // A3
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
                'deleted_at' => null,
            ],
        ]);
    }
}

