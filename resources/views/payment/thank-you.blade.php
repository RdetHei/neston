@extends('layouts.landing')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Pembayaran Berhasil</h1>
        <p class="text-gray-600 mb-6">Terima kasih. Transaksi parkir Anda telah selesai.</p>
        <div class="bg-gray-50 rounded-xl p-4 mb-6 text-left">
            <p class="text-sm text-gray-600">Plat Nomor</p>
            <p class="font-bold text-lg">{{ $transaksi->kendaraan->plat_nomor ?? '-' }}</p>
            <p class="text-sm text-gray-600 mt-2">Total Dibayar</p>
            <p class="font-bold text-green-600">Rp {{ number_format($transaksi->biaya_total ?? 0, 0, ',', '.') }}</p>
        </div>
        <a href="{{ url('/') }}" class="inline-block px-6 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700">
            Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
