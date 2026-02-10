@extends('layouts.app')

@section('title', 'Parkir Masuk')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Kendaraan Parkir Aktif</h2>
        <a href="{{ route('transaksi.create-check-in') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Tambah Parkir
        </a>
    </div>

    @if($message = Session::get('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ $message }}
        </div>
    @endif

    @if($message = Session::get('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 mx-4 sm:mx-6 lg:mx-8">
            {{ $message }}
        </div>
    @endif

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        @if($transaksis->count())
        <table class="w-full table-auto divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No Transaksi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plat Nomor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Area</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu Masuk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Operator</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($transaksis as $transaksi)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-bold text-blue-600">
                        #{{ str_pad($transaksi->id_parkir, 8, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-6 py-4 text-sm font-bold text-gray-800">
                        {{ $transaksi->kendaraan->plat_nomor ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $transaksi->kendaraan->jenis_kendaraan ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $transaksi->area->nama_area ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $transaksi->waktu_masuk->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @php
                            $durasi = now()->diffInMinutes($transaksi->waktu_masuk);
                            $jam = intdiv($durasi, 60);
                            $menit = $durasi % 60;
                        @endphp
                        <span class="font-semibold">{{ $jam }}j {{ $menit }}m</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $transaksi->user->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $transaksi->catatan ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm space-x-2">
                        <form action="{{ route('transaksi.checkOut', $transaksi->id_parkir) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs"
                                    onclick="return confirm('Catat kendaraan keluar?')">
                                Keluar
                            </button>
                        </form>
                        @if(auth()->user()->role === 'admin')
                        <a href="{{ route('transaksi.print', $transaksi->id_parkir) }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs inline-block">
                            Struk
                        </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="px-6 py-4 border-t bg-gray-50">
            {{ $transaksis->links() }}
        </div>
        @else
        <div class="px-6 py-8 text-center text-gray-500">
            <p class="text-lg">Tidak ada kendaraan yang sedang parkir</p>
        </div>
        @endif
    </div>

        </div>
    </div>
@endsection
