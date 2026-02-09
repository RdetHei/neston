@extends('layouts.app')

@section('title', 'Pembayaran Manual')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    @component('components.form-card', [
        'backUrl' => route('payment.create', $transaksi->id_parkir),
        'cancelText' => 'Batal',
        'title' => 'Pembayaran Manual',
        'cardIcon' => '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        'cardTitle' => 'Verifikasi Pembayaran Manual',
        'cardDescription' => 'Konfirmasi nominal dan catatan untuk transaksi parkir',
        'action' => route('payment.manual-process', $transaksi->id_parkir),
        'method' => 'POST',
        'submitText' => 'Konfirmasi Pembayaran'
    ])
        <!-- Detail transaksi (bukan field form-card default) -->
        <div class="mb-6 pb-6 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Detail Kendaraan & Transaksi</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="bg-gray-50 p-3 rounded-xl border border-gray-200">
                    <p class="text-xs text-gray-500 uppercase">Plat Nomor</p>
                    <p class="font-bold text-gray-900">{{ $transaksi->kendaraan->plat_nomor }}</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-xl border border-gray-200">
                    <p class="text-xs text-gray-500 uppercase">Jenis</p>
                    <p class="font-bold text-gray-900">{{ $transaksi->kendaraan->jenis_kendaraan }}</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-xl border border-gray-200">
                    <p class="text-xs text-gray-500 uppercase">Durasi</p>
                    <p class="font-bold text-gray-900">{{ $transaksi->durasi_jam }} jam</p>
                </div>
                <div class="bg-green-50 p-3 rounded-xl border-2 border-green-200">
                    <p class="text-xs text-green-700 uppercase font-semibold">Total Bayar</p>
                    <p class="text-xl font-bold text-green-700" id="biaya-total-display">Rp {{ number_format($transaksi->biaya_total, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <input type="hidden" id="biaya-total-value" value="{{ $transaksi->biaya_total }}">

        <!-- Nominal -->
        <div>
            <label for="nominal" class="block text-sm font-semibold text-gray-700 mb-2">Nominal Pembayaran <span class="text-red-500">*</span></label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">Rp</span>
                <input type="number" name="nominal" id="nominal" required min="0" step="1"
                       value="{{ old('nominal', $transaksi->biaya_total) }}"
                       class="block w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('nominal') border-red-500 @enderror">
            </div>
            @error('nominal')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            <p class="mt-1 text-xs text-gray-500">Dapat diubah jika ada diskon atau koreksi.</p>
            <div id="nominal-warning" class="hidden mt-2 p-3 bg-amber-50 border border-amber-200 rounded-xl text-sm text-amber-800">
                Nominal lebih rendah dari total biaya. Pastikan ini disengaja (diskon/koreksi).
            </div>
        </div>

        <!-- Keterangan -->
        <div>
            <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-2">Keterangan / Catatan</label>
            <textarea name="keterangan" id="keterangan" rows="3" placeholder="Cth: Diskon khusus, pembayaran tunai, dsb."
                      class="block w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('keterangan') border-red-500 @enderror">{{ old('keterangan') }}</textarea>
            @error('keterangan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <!-- Info petugas -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <p class="text-sm text-blue-900">
                <span class="font-semibold">Petugas:</span> {{ auth()->user()->name }} &nbsp;|&nbsp;
                <span class="font-semibold">Waktu:</span> {{ now()->format('d/m/Y H:i') }}
            </p>
        </div>
    @endcomponent
</div>

<script>
(function() {
    var biayaTotal = parseFloat(document.getElementById('biaya-total-value').value) || 0;
    var nominalInput = document.getElementById('nominal');
    var warningEl = document.getElementById('nominal-warning');

    function checkNominal() {
        var val = parseFloat(nominalInput.value) || 0;
        if (biayaTotal > 0 && val > 0 && val < biayaTotal) {
            warningEl.classList.remove('hidden');
        } else {
            warningEl.classList.add('hidden');
        }
    }

    nominalInput.addEventListener('input', checkNominal);
    nominalInput.addEventListener('change', checkNominal);
    checkNominal();
})();
</script>
@endsection
