<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kendaraan;
use App\Models\Tarif;
use App\Models\AreaParkir;

class DiagnoseCheckIn extends Command
{
    protected $signature = 'diagnose:checkin';
    protected $description = 'Diagnose the check-in form data availability';

    public function handle()
    {
        $this->info('Diagnosing Check-In Functionality...');
        $this->newLine();

        // Check Kendaraan
        $kendaraanCount = Kendaraan::count();
        $this->info("Kendaraan Records: $kendaraanCount");
        if ($kendaraanCount > 0) {
            $this->table(['ID', 'Plat Nomor', 'Jenis', 'Pemilik'], 
                Kendaraan::select('id_kendaraan', 'plat_nomor', 'jenis_kendaraan', 'pemilik')
                    ->limit(5)->get()->toArray()
            );
        } else {
            $this->warn('No kendaraan found. Add some vehicles first.');
        }
        $this->newLine();

        // Check Tarif
        $tarifCount = Tarif::count();
        $this->info("Tarif Records: $tarifCount");
        if ($tarifCount > 0) {
            $this->table(['ID', 'Jenis Kendaraan', 'Tarif/Jam'], 
                Tarif::select('id_tarif', 'jenis_kendaraan', 'tarif_perjam')
                    ->limit(5)->get()->toArray()
            );
        } else {
            $this->warn('No tarif found. Add some tarif first.');
        }
        $this->newLine();

        // Check Area Parkir
        $areaCount = AreaParkir::count();
        $this->info("Area Parkir Records: $areaCount");
        if ($areaCount > 0) {
            $this->table(['ID', 'Nama Area', 'Kapasitas', 'Terisi'], 
                AreaParkir::select('id_area', 'nama_area', 'kapasitas', 'terisi')
                    ->limit(5)->get()->toArray()
            );
        } else {
            $this->warn('No area parkir found. Add some parking areas first.');
        }
        $this->newLine();

        if ($kendaraanCount > 0 && $tarifCount > 0 && $areaCount > 0) {
            $this->info('All required data is available. Check-in should work.');
        } else {
            $this->warn('Missing required data. Please add the missing records.');
        }
    }
}
