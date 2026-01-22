@extends('layouts.app')

@section('title','Edit Log Aktivitas')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Log Aktivitas</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('log-aktivitas.update', $item->id_log) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="id_user" class="form-label">User</label>
                            <select name="id_user" id="id_user" class="form-control @error('id_user') is-invalid @enderror">
                                <option value="">-- Pilih User --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('id_user', $item->id_user) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->role }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_user')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="aktivitas" class="form-label">Aktivitas</label>
                            <textarea name="aktivitas" id="aktivitas" rows="3" class="form-control @error('aktivitas') is-invalid @enderror" placeholder="Deskripsi aktivitas">{{ old('aktivitas', $item->aktivitas) }}</textarea>
                            @error('aktivitas')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="waktu_aktivitas" class="form-label">Waktu Aktivitas</label>
                            <input type="datetime-local" name="waktu_aktivitas" id="waktu_aktivitas" class="form-control @error('waktu_aktivitas') is-invalid @enderror" value="{{ old('waktu_aktivitas', $item->waktu_aktivitas?->format('Y-m-d\TH:i')) }}">
                            @error('waktu_aktivitas')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('log-aktivitas.index') }}" class="btn btn-secondary mr-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
