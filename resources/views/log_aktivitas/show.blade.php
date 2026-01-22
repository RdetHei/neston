@extends('layouts.app')

@section('title','Detail Log Aktivitas')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Log Aktivitas #{{ $item->id_log }}</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <p class="text-muted mb-0"><strong>ID Log</strong></p>
                        </div>
                        <div class="col-sm-9">
                            <p class="mb-0">{{ $item->id_log }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <p class="text-muted mb-0"><strong>User</strong></p>
                        </div>
                        <div class="col-sm-9">
                            <p class="mb-0">
                                <span class="badge badge-primary">{{ $item->user?->name ?? 'N/A' }}</span>
                                <small class="text-muted">({{ $item->user?->role ?? '-' }})</small>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <p class="text-muted mb-0"><strong>Aktivitas</strong></p>
                        </div>
                        <div class="col-sm-9">
                            <p class="mb-0">{{ $item->aktivitas }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <p class="text-muted mb-0"><strong>Waktu Aktivitas</strong></p>
                        </div>
                        <div class="col-sm-9">
                            <p class="mb-0">{{ $item->waktu_aktivitas?->format('d/m/Y H:i:s') ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <p class="text-muted mb-0"><strong>Dibuat</strong></p>
                        </div>
                        <div class="col-sm-9">
                            <p class="mb-0">{{ $item->created_at?->format('d/m/Y H:i:s') ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <p class="text-muted mb-0"><strong>Diupdate</strong></p>
                        </div>
                        <div class="col-sm-9">
                            <p class="mb-0">{{ $item->updated_at?->format('d/m/Y H:i:s') ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('log-aktivitas.index') }}" class="btn btn-secondary mr-2">Kembali</a>
                        <a href="{{ route('log-aktivitas.edit', $item->id_log) }}" class="btn btn-warning">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
