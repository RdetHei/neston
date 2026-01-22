@extends('layouts.app')

@section('title','Log Aktivitas')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Log Aktivitas</h1>
        <a href="{{ route('log-aktivitas.create') }}" class="btn btn-primary">+ Buat Log</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Aktivitas</th>
                            <th>Waktu Aktivitas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td><strong>#{{ $item->id_log }}</strong></td>
                                <td>{{ $item->user?->name ?? 'N/A' }}</td>
                                <td>{{ $item->aktivitas }}</td>
                                <td>{{ $item->waktu_aktivitas?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('log-aktivitas.show', $item->id_log) }}" class="btn btn-sm btn-info" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('log-aktivitas.edit', $item->id_log) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('log-aktivitas.destroy', $item->id_log) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Tidak ada data log aktivitas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($items->hasPages())
                <div class="mt-3">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
