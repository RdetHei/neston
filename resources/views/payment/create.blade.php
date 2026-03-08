
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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- NestonPay Wallet -->
            <div class="bg-white border-2 border-indigo-500 rounded-3xl p-8 hover:border-indigo-600 hover:shadow-xl transition-all cursor-pointer relative overflow-hidden group"
                 onclick="document.getElementById('form-nestonpay').submit()">
                <form id="form-nestonpay" action="{{ route('user.saldo.pay', $transaksi->id_parkir) }}" method="POST" class="hidden">
                    @csrf
                </form>
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-indigo-50 rounded-full group-hover:scale-150 transition-transform"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <span class="text-[10px] font-bold bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full uppercase tracking-widest">INSTANT</span>
                    </div>
                    <h3 class="text-xl font-extrabold text-gray-900 mb-2">NestonPay Wallet</h3>
                    <p class="text-sm text-gray-500 mb-6 leading-relaxed">Bayar instan menggunakan saldo dompet digital Anda.</p>
                    
                    <div class="p-4 bg-indigo-50 rounded-2xl border border-indigo-100 mb-6">
                        <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-1">Saldo Anda</p>
                        <p class="text-lg font-bold text-indigo-700">Rp {{ number_format(Auth::user()->saldo, 0, ',', '.') }}</p>
                    </div>

                    @if(Auth::user()->saldo < $transaksi->biaya_total)
                        <div class="bg-amber-50 p-3 rounded-xl text-amber-800 text-xs font-bold text-center border border-amber-100">
                            Saldo tidak cukup. Silakan Top Up.
                        </div>
                    @else
                        <div class="bg-indigo-600 p-4 rounded-2xl text-white font-bold text-center shadow-lg shadow-indigo-100 group-hover:bg-indigo-700 transition-colors">
                            Bayar Sekarang
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bayar dengan Midtrans (GoPay, VA, dll) -->
            <div class="bg-white border-2 border-emerald-500 rounded-3xl p-8 hover:border-emerald-600 hover:shadow-xl transition-all cursor-pointer relative overflow-hidden group"
                 onclick="document.location.href='{{ route('payment.midtrans', $transaksi->id_parkir) }}'">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-emerald-50 rounded-full group-hover:scale-150 transition-transform"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <span class="text-[10px] font-bold bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full uppercase tracking-widest">ONLINE</span>
                    </div>
                    <h3 class="text-xl font-extrabold text-gray-900 mb-2">Midtrans Online</h3>
                    <p class="text-sm text-gray-500 mb-6 leading-relaxed">GoPay, OVO, Dana, QRIS, dan Transfer Bank.</p>
                    
                    <ul class="space-y-2 text-xs text-gray-600 mb-10">
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> GoPay & QRIS
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Virtual Account (BCA, BRI, dll)
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Kartu Kredit/Debit
                        </li>
                    </ul>

                    <div class="bg-emerald-600 p-4 rounded-2xl text-white font-bold text-center shadow-lg shadow-emerald-100 group-hover:bg-emerald-700 transition-colors">
                        Pilih Metode Online
                    </div>
                </div>
            </div>
        </div>
    @endcomponent
@endsection


