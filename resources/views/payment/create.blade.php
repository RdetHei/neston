
@extends('layouts.app')

@section('title', 'Pilih Metode Pembayaran')

@section('content')
    @component('components.form-card', [
        'backUrl' => route('transaksi.index', ['status' => 'masuk']),
        'title' => 'Pilih Metode Pembayaran',
        'description' => 'Pilih metode pembayaran untuk transaksi ini',
        'cardIcon' => '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>',
        'cardTitle' => 'Rincian Pembayaran',
        'cardDescription' => 'Ringkasan transaksi dan pilihan metode pembayaran',
        'action' => '#', // Dummy action as there's no single form submission here
        'method' => 'GET', // Dummy method
        'submitText' => '', // No primary submit button
        'cancelText' => 'Kembali ke Dashboard'
    ])
        <!-- Ringkasan Transaksi -->
        <div class="bg-blue-50 border border-blue-300 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-bold text-blue-900 mb-4">Ringkasan Transaksi</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-blue-700">Plat Nomor</p>
                    <p class="text-xl font-bold text-blue-900">{{ $transaksi->kendaraan->plat_nomor }}</p>
                </div>
                <div>
                    <p class="text-sm text-blue-700">Durasi</p>
                    <p class="text-xl font-bold text-blue-900">{{ $transaksi->durasi_jam }} jam</p>
                </div>
                <div>
                    <p class="text-sm text-blue-700">Tarif/Jam</p>
                    <p class="text-xl font-bold text-blue-900">Rp {{ number_format($transaksi->tarif->tarif_perjam, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-blue-700">Total Bayar</p>
                    <p class="text-2xl font-bold text-green-600">Rp {{ number_format($transaksi->biaya_total, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Pilihan Metode Pembayaran -->
        <div class="grid grid-cols-1 gap-6">
            <!-- Bayar dengan Midtrans (GoPay, VA, dll) -->
            <div class="bg-white border-2 border-green-500 rounded-lg p-8 hover:border-green-600 hover:shadow-lg transition cursor-pointer"
                 onclick="document.location.href='{{ route('payment.midtrans', $transaksi->id_parkir) }}'">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-bold bg-green-100 text-green-700 px-3 py-1 rounded-full">ONLINE</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Bayar dengan Midtrans</h3>
                <p class="text-gray-600 mb-4">GoPay, transfer bank, kartu kredit, dan metode online lainnya</p>
                <ul class="space-y-2 text-sm text-gray-700 mb-6">
                    <li class="flex items-center gap-2">
                        <span class="text-green-600">•</span> GoPay, OVO, DANA, LinkAja
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-green-600">•</span> Transfer bank (BCA, BNI, BRI, dll)
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-green-600">•</span> Kartu kredit/debit
                    </li>
                </ul>
                <div class="bg-green-50 p-3 rounded text-green-800 font-semibold text-center">
                    Bayar Online dengan Midtrans
                </div>
            </div>
        </div>
    @endcomponent
@endsection


