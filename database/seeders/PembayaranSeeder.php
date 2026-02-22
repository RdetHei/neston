<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pembayaran;
use App\Models\Transaksi;
use Carbon\Carbon;

class PembayaranSeeder extends Seeder
{
    public function run(): void
    {
        // Create pembayaran for transaksi that were marked as paid
        $paidTx = Transaksi::where('status_pembayaran','berhasil')->get();
        foreach ($paidTx as $tx) {
            $p = Pembayaran::create([
                'id_parkir' => $tx->id_parkir,
                'nominal' => $tx->biaya_total ?? 0,
                'metode' => 'midtrans',
                'status' => 'berhasil',
                'keterangan' => 'Seeded payment',
                'id_user' => $tx->id_user,
                'waktu_pembayaran' => Carbon::now()->subHours(5),
            ]);

            // link back to transaksi
            $tx->update(['id_pembayaran' => $p->id_pembayaran]);
        }
    }
}
