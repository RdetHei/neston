<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kendaraan;
use App\Models\Tarif;
use App\Models\AreaParkir;

class SeedCheckInData extends Command
{
    protected $signature = 'seed:checkin-data';
    protected $description = 'Seed required data for check-in functionality (for testing only)';

    public function handle()
    {
        $this->info('Seeding check-in test data...');

        // Seed Kendaraan
        $kendaraans = [
            ['plat_nomor' => 'B 1001 ABC', 'jenis_kendaraan' => 'Motor', 'warna' => 'Hitam', 'pemilik' => 'Budi'],
            ['plat_nomor' => 'B 2002 DEF', 'jenis_kendaraan' => 'Motor', 'warna' => 'Merah', 'pemilik' => 'Ani'],
            ['plat_nomor' => 'B 3003 GHI', 'jenis_kendaraan' => 'Mobil', 'warna' => 'Putih', 'pemilik' => 'Citra'],
            ['plat_nomor' => 'B 4004 JKL', 'jenis_kendaraan' => 'Mobil', 'warna' => 'Biru', 'pemilik' => 'Dedi'],
        ];

        foreach ($kendaraans as $k) {
            Kendaraan::firstOrCreate(['plat_nomor' => $k['plat_nomor']], $k);
        }
        $this->line('[OK] Kendaraan seeded');

        // Seed Tarif
        $tarifs = [
            ['jenis_kendaraan' => 'Motor', 'tarif_perjam' => 3000],
            ['jenis_kendaraan' => 'Mobil', 'tarif_perjam' => 5000],
        ];

        foreach ($tarifs as $t) {
            Tarif::firstOrCreate(['jenis_kendaraan' => $t['jenis_kendaraan']], $t);
        }
        $this->line('[OK] Tarif seeded');

        // Seed AreaParkir
        $areas = [
            ['nama_area' => 'Area A - Level 1', 'kapasitas' => 50, 'terisi' => 0],
            ['nama_area' => 'Area B - Level 2', 'kapasitas' => 40, 'terisi' => 0],
            ['nama_area' => 'Area C - Outdoor', 'kapasitas' => 30, 'terisi' => 0],
        ];

        foreach ($areas as $a) {
            AreaParkir::firstOrCreate(['nama_area' => $a['nama_area']], $a);
        }
        $this->line('[OK] Area Parkir seeded');

        $this->info('All test data seeded successfully!');
    }
}
