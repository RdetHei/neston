@extends('layouts.app')

@section('title','Report Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Report Pembayaran</h1>
        <form action="{{ route('report.pembayaran.export-csv') }}" method="GET" class="form-inline">
            <input type="hidden" name="tanggal_dari" value="{{ request('tanggal_dari') }}">
            <input type="hidden" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <button type="submit" class="btn btn-success"><i class="fas fa-download"></i> Export CSV</button>
        </form>
    </div>

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('report.pembayaran') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="tanggal_dari" class="form-label">Tanggal Dari</label>
                    <input type="date" class="form-control" name="tanggal_dari" value="{{ request('tanggal_dari') }}">
                </div>

                <div class="col-md-3">
                    <label for="tanggal_sampai" class="form-label">Tanggal Sampai</label>
                    <input type="date" class="form-control" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}">
                </div>

                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" name="status">
                        <option value="">-- Semua --</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="berhasil" {{ request('status') === 'berhasil' ? 'selected' : '' }}>Berhasil</option>
                        <option value="gagal" {{ request('status') === 'gagal' ? 'selected' : '' }}>Gagal</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="metode" class="form-label">Metode</label>
                    <select class="form-control" name="metode">
                        <option value="">-- Semua --</option>
                        <option value="manual" {{ request('metode') === 'manual' ? 'selected' : '' }}>Manual</option>
                        <option value="qr_scan" {{ request('metode') === 'qr_scan' ? 'selected' : '' }}>QR Scan</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
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
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pembayaran</div>
                    <div class="h6 mb-0 font-weight-bold">{{ $count_pembayaran }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-success shadow">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Nominal</div>
                    <div class="h6 mb-0 font-weight-bold">Rp {{ number_format($total_nominal, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-info shadow">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Rata-rata Nominal</div>
                    <div class="h6 mb-0 font-weight-bold">Rp {{ number_format($avg_nominal, 0, ',', '.') }}</div>
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
                            <th>Transaksi</th>
                            <th>Nominal</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Petugas</th>
                            <th>Waktu Pembayaran</th>
                            <th>Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembayarans as $item)
                            <tr>
                                <td><strong>#{{ $item->id_pembayaran }}</strong></td>
                                <td>{{ $item->id_parkir }}</td>
                                <td><strong>Rp {{ number_format($item->nominal, 0, ',', '.') }}</strong></td>
                                <td>
                                    @if($item->metode === 'manual')
                                        <span class="badge bg-secondary">Manual</span>
                                    @else
                                        <span class="badge bg-primary">QR Scan</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status === 'berhasil')
                                        <span class="badge bg-success">Berhasil</span>
                                    @elseif($item->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Gagal</span>
                                    @endif
                                </td>
                                <td>{{ $item->petugas?->name ?? '-' }}</td>
                                <td>{{ $item->waktu_pembayaran?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td>{{ $item->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Tidak ada data pembayaran</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pembayarans->hasPages())
                <div class="mt-3">
                    {{ $pembayarans->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
