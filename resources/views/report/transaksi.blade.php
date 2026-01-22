@extends('layouts.app')

@section('title','Report Transaksi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Report Transaksi</h1>
        <form action="{{ route('report.transaksi.export-csv') }}" method="GET" class="form-inline">
            <input type="hidden" name="tanggal_dari" value="{{ request('tanggal_dari') }}">
            <input type="hidden" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <input type="hidden" name="id_area" value="{{ request('id_area') }}">
            <button type="submit" class="btn btn-success"><i class="fas fa-download"></i> Export CSV</button>
        </form>
    </div>

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('report.transaksi') }}" method="GET" class="row g-3">
                <div class="col-md-2">
                    <label for="tanggal_dari" class="form-label">Tanggal Dari</label>
                    <input type="date" class="form-control" name="tanggal_dari" value="{{ request('tanggal_dari') }}">
                </div>

                <div class="col-md-2">
                    <label for="tanggal_sampai" class="form-label">Tanggal Sampai</label>
                    <input type="date" class="form-control" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}">
                </div>

                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" name="status">
                        <option value="">-- Semua --</option>
                        <option value="masuk" {{ request('status') === 'masuk' ? 'selected' : '' }}>Masuk</option>
                        <option value="keluar" {{ request('status') === 'keluar' ? 'selected' : '' }}>Keluar</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="id_area" class="form-label">Area Parkir</label>
                    <select class="form-control" name="id_area">
                        <option value="">-- Semua Area --</option>
                        @foreach(\App\Models\AreaParkir::all() as $area)
                            <option value="{{ $area->id_area }}" {{ request('id_area') == $area->id_area ? 'selected' : '' }}>
                                {{ $area->nama_area }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Transaksi</div>
                    <div class="h6 mb-0 font-weight-bold">{{ $total_transaksi }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-success shadow">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Biaya</div>
                    <div class="h6 mb-0 font-weight-bold">Rp {{ number_format($total_biaya, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-info shadow">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Rata-rata Durasi</div>
                    <div class="h6 mb-0 font-weight-bold">{{ number_format($durasi_rata, 1, ',', '.') }} jam</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-warning shadow">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Periode</div>
                    <div class="h6 mb-0 font-weight-bold">
                        {{ request('tanggal_dari') ?: 'Awal' }}
                        s/d
                        {{ request('tanggal_sampai') ?: 'Akhir' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Plat Nomor</th>
                            <th>Area</th>
                            <th>Waktu Masuk</th>
                            <th>Waktu Keluar</th>
                            <th>Durasi (jam)</th>
                            <th>Biaya</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $item)
                            <tr>
                                <td><strong>#{{ $item->id_parkir }}</strong></td>
                                <td>{{ $item->kendaraan?->plat_nomor ?? '-' }}</td>
                                <td>{{ $item->area?->nama_area ?? '-' }}</td>
                                <td>{{ $item->waktu_masuk?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td>{{ $item->waktu_keluar?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td>{{ $item->durasi_jam ?? '-' }}</td>
                                <td><strong>Rp {{ number_format($item->biaya_total, 0, ',', '.') }}</strong></td>
                                <td>
                                    @if($item->status === 'masuk')
                                        <span class="badge bg-primary">Masuk</span>
                                    @else
                                        <span class="badge bg-secondary">Keluar</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status_pembayaran === 'berhasil')
                                        <span class="badge bg-success">Berhasil</span>
                                    @elseif($item->status_pembayaran === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">Tidak ada data transaksi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($transaksis->hasPages())
                <div class="mt-3">
                    {{ $transaksis->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
