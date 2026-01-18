<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pembayaran;
use App\Models\Transaksi;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Tampil form pembayaran untuk transaksi
     */
    public function create($id_parkit)
    {
        $transaksi = Transaksi::with(['kendaraan', 'tarif', 'user', 'area'])
            ->findOrFail($id_parkit);

        // Cek apakah sudah ada pembayaran
        if ($transaksi->status_pembayaran === 'sudah_bayar') {
            return back()->with('error', 'Transaksi ini sudah dibayar');
        }

        $qr_data = route('payment.confirm-qr', $id_parkit);

        return view('payment.create', compact('transaksi', 'qr_data'));
    }

    /**
     * Pembayaran Manual - Form Konfirmasi
     */
    public function manual_confirm($id_parkit)
    {
        $transaksi = Transaksi::with(['kendaraan', 'tarif', 'user', 'area'])
            ->findOrFail($id_parkit);

        return view('payment.manual-confirm', compact('transaksi'));
    }

    /**
     * Proses Pembayaran Manual
     */
    public function manual_process(Request $request, $id_parkit)
    {
        $transaksi = Transaksi::findOrFail($id_parkit);

        $request->validate([
            'nominal' => 'required|numeric|min:' . ($transaksi->biaya_total ?? 0),
            'keterangan' => 'nullable|string|max:500',
        ], [
            'nominal.min' => 'Nominal pembayaran harus minimal Rp ' . number_format($transaksi->biaya_total ?? 0, 0, ',', '.'),
        ]);

        try {
            if ($transaksi->status_pembayaran === 'sudah_bayar') {
                return back()->with('error', 'Transaksi ini sudah dibayar');
            }

            // Buat record pembayaran
            $pembayaran = Pembayaran::create([
                'id_parkit' => $id_parkit,
                'nominal' => $request->nominal,
                'metode' => 'manual',
                'status' => 'berhasil',
                'keterangan' => $request->keterangan ?? 'Pembayaran manual oleh petugas',
                'id_user' => Auth::id(),
                'waktu_pembayaran' => Carbon::now(),
            ]);

            // Update transaksi
            $transaksi->update([
                'status_pembayaran' => 'sudah_bayar',
                'id_pembayaran' => $pembayaran->id_pembayaran,
            ]);

            return redirect()->route('payment.success', $id_parkit)
                ->with('success', 'Pembayaran berhasil diproses');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Halaman pembayaran via QR Scan
     */
    public function qr_scan($id_parkit)
    {
        $transaksi = Transaksi::with(['kendaraan', 'tarif'])
            ->findOrFail($id_parkit);

        return view('payment.qr-scan', compact('transaksi'));
    }

    /**
     * Konfirmasi pembayaran via QR Scan
     */
    public function confirm_qr($id_parkit)
    {
        try {
            $transaksi = Transaksi::findOrFail($id_parkit);

            if ($transaksi->status_pembayaran === 'sudah_bayar') {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi ini sudah dibayar'
                ]);
            }

            // Buat record pembayaran
            $pembayaran = Pembayaran::create([
                'id_parkit' => $id_parkit,
                'nominal' => $transaksi->biaya_total,
                'metode' => 'qr_scan',
                'status' => 'berhasil',
                'keterangan' => 'Pembayaran otomatis via scan QR oleh pengendara',
                'id_user' => Auth::id(),
                'waktu_pembayaran' => Carbon::now(),
            ]);

            // Update transaksi
            $transaksi->update([
                'status_pembayaran' => 'sudah_bayar',
                'id_pembayaran' => $pembayaran->id_pembayaran,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil diproses',
                'redirect' => route('payment.success', $id_parkit)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Halaman sukses pembayaran
     */
    public function success($id_parkit)
    {
        $transaksi = Transaksi::with(['kendaraan', 'tarif', 'pembayaran', 'user', 'area'])
            ->findOrFail($id_parkit);

        return view('payment.success', compact('transaksi'));
    }

    /**
     * Riwayat pembayaran
     */
    public function index()
    {
        $pembayarans = Pembayaran::with(['transaksi', 'petugas'])
            ->orderBy('id_pembayaran', 'desc')
            ->paginate(15);

        return view('payment.index', compact('pembayarans'));
    }
}
