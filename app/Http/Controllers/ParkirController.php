<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use App\Models\Kendaraan;
use App\Models\Tarif;
use App\Models\AreaParkir;
use Carbon\Carbon;

class ParkirController extends Controller
{
    /**
     * Display a listing of parked items.
     */
    public function index()
    {
        $transaksis = Transaksi::with(['kendaraan', 'tarif', 'user', 'area'])
            ->where('status', 'masuk')
            ->orderBy('waktu_masuk', 'desc')
            ->paginate(15);
        
        return view('parkir.index', compact('transaksis'));
    }

    /**
     * Show the form for creating a new parkir entry.
     */
    public function create()
    {
        $kendaraans = Kendaraan::orderBy('plat_nomor')->get();
        $tarifs = Tarif::orderBy('jenis_kendaraan')->get();
        $areas = AreaParkir::orderBy('nama_area')->get();
        
        return view('parkir.create', compact('kendaraans', 'tarifs', 'areas'));
    }

    /**
     * Store a newly created parkir entry.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_kendaraan' => 'required|exists:tb_kendaraan,id_kendaraan',
            'id_tarif' => 'required|exists:tb_tarif,id_tarif',
            'id_area' => 'required|exists:tb_area_parkir,id_area',
        ]);

        try {
            $area = AreaParkir::findOrFail($request->id_area);
            
            // Check kapasitas
            if ($area->terisi >= $area->kapasitas) {
                return back()->with('error', 'Kapasitas area parkir sudah penuh');
            }

            $transaksi = Transaksi::create([
                'id_kendaraan' => $request->id_kendaraan,
                'id_tarif' => $request->id_tarif,
                'id_area' => $request->id_area,
                'id_user' => Auth::id(),
                'waktu_masuk' => Carbon::now(),
                'status' => 'masuk',
            ]);

            // Update kapasitas
            $area->increment('terisi');

            return redirect()->route('parkir.index')
                ->with('success', 'Kendaraan berhasil dicatat masuk parkir');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mencatat transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource (checkout/keluar).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            // Validation rules can be added if needed
        ]);

        try {
            $transaksi = Transaksi::findOrFail($id);
            
            if ($transaksi->status === 'keluar') {
                return back()->with('error', 'Kendaraan ini sudah tercatat keluar');
            }

            // Calculate duration and cost
            $waktu_keluar = Carbon::now();
            $durasi_jam = ceil($waktu_keluar->diffInMinutes($transaksi->waktu_masuk) / 60);
            $biaya_total = $durasi_jam * $transaksi->tarif->tarif_perjam;

            $transaksi->update([
                'waktu_keluar' => $waktu_keluar,
                'durasi_jam' => $durasi_jam,
                'biaya_total' => $biaya_total,
                'status' => 'keluar',
            ]);

            // Decrement kapasitas area
            $area = AreaParkir::findOrFail($transaksi->id_area);
            if ($area->terisi > 0) {
                $area->decrement('terisi');
            }

            return redirect()->route('payment.create', $transaksi->id_parkit)
                ->with('success', 'Kendaraan berhasil dicatat keluar. Silakan lanjut ke pembayaran');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Print/preview a receipt for the given entry.
     */
    public function print($id)
    {
        $transaksi = Transaksi::with(['kendaraan', 'tarif', 'user', 'area'])->findOrFail($id);
        
        return view('parkir.receipt', compact('transaksi'));
    }
}
