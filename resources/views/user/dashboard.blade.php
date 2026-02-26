@extends('layouts.app')

@section('content')
    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto space-y-8">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold tracking-wide text-emerald-600 uppercase">Selamat datang</p>
                    <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">
                        {{ $user->name }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Ringkasan aktivitas parkir pribadi Anda.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('user.vehicles.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                        </svg>
                        Kendaraan saya
                    </a>
                    <a href="{{ route('user.bookings') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-medium text-emerald-800 shadow-sm hover:bg-emerald-100">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Booking slot
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl p-4 border border-gray-200">
                    <p class="text-xs font-medium text-gray-500">Kendaraan saya</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ $totalKendaraan }}</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-200">
                    <p class="text-xs font-medium text-gray-500">Total transaksi</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ $totalTransaksi }}</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-200">
                    <p class="text-xs font-medium text-gray-500">Parkir aktif</p>
                    <p class="mt-2 text-2xl font-bold text-emerald-600">{{ $transaksiAktif }}</p>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-gray-200">
                    <p class="text-xs font-medium text-gray-500">Total pengeluaran</p>
                    <p class="mt-2 text-xl font-bold text-gray-900">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl border border-gray-200">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-semibold text-gray-900">Parkir aktif saya</h2>
                            <p class="text-xs text-gray-500">Kendaraan yang saat ini masih tercatat parkir.</p>
                        </div>
                    </div>
                    <div class="p-5">
                        @if($transaksiAktif > 0 && $riwayatTransaksi->where('status', 'masuk')->count())
                            <ul class="divide-y divide-gray-100">
                                @foreach($riwayatTransaksi->where('status', 'masuk') as $trx)
                                    <li class="py-3 flex items-center justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">
                                                {{ $trx->kendaraan->plat_nomor ?? '-' }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                Masuk: {{ \Carbon\Carbon::parse($trx->waktu_masuk)->format('d M Y, H:i') }} • Area: {{ $trx->area->nama_area ?? '-' }}
                                            </p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500">Tidak ada parkir aktif saat ini.</p>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-semibold text-gray-900">Riwayat parkir terakhir</h2>
                            <p class="text-xs text-gray-500">5 transaksi parkir terakhir atas nama Anda.</p>
                        </div>
                    </div>
                    <div class="p-5">
                        @if($riwayatTransaksi->count())
                            <ul class="divide-y divide-gray-100">
                                @foreach($riwayatTransaksi as $trx)
                                    <li class="py-3 flex items-center justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">
                                                {{ $trx->kendaraan->plat_nomor ?? '-' }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($trx->waktu_masuk)->format('d M Y, H:i') }}
                                                @if($trx->waktu_keluar)
                                                    — {{ \Carbon\Carbon::parse($trx->waktu_keluar)->format('H:i') }}
                                                @endif
                                                • Area: {{ $trx->area->nama_area ?? '-' }}
                                            </p>
                                        </div>
                                        @if($trx->biaya_total)
                                            <p class="text-xs font-semibold text-gray-900">
                                                Rp {{ number_format($trx->biaya_total, 0, ',', '.') }}
                                            </p>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500">Belum ada riwayat transaksi parkir.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900">Riwayat pembayaran saya</h2>
                        <p class="text-xs text-gray-500">5 pembayaran terakhir yang tercatat dengan akun Anda.</p>
                    </div>
                </div>
                <div class="p-5">
                    @if($riwayatPembayaran->count())
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 border-b border-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Waktu</th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Metode</th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Nominal</th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($riwayatPembayaran as $pay)
                                        <tr>
                                            <td class="px-4 py-2">
                                                <div class="text-xs text-gray-900">
                                                    {{ optional($pay->waktu_pembayaran)->format('d M Y') ?? '-' }}
                                                </div>
                                                <div class="text-[11px] text-gray-500">
                                                    {{ optional($pay->waktu_pembayaran)->format('H:i') ?? '' }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-2 text-xs text-gray-700">
                                                {{ $pay->metode ?? '-' }}
                                            </td>
                                            <td class="px-4 py-2 text-xs font-semibold text-gray-900">
                                                Rp {{ number_format($pay->nominal, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-2">
                                                @php
                                                    $status = $pay->status ?? 'pending';
                                                    $badge = $status === 'berhasil'
                                                        ? 'bg-emerald-100 text-emerald-800'
                                                        : ($status === 'gagal'
                                                            ? 'bg-red-100 text-red-800'
                                                            : 'bg-yellow-100 text-yellow-800');
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium {{ $badge }}">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">Belum ada pembayaran yang tercatat untuk akun ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
