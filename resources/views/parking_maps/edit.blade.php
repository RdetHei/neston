@extends('layouts.app')

@section('title', 'Edit Layout Peta Parkir')

@section('content')
    @component('components.form-card', [
        'backUrl' => route('parking-maps.index'),
        'title' => 'Edit Layout Peta Parkir',
        'description' => 'Ubah konfigurasi floor plan seperti nama, kode, dan ukuran.',
        'cardIcon' => '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14M3 7a2 2 0 012-2h14m-4-2v4m0 0L14 5m1 2l1 1m-4 2h.01M7 12h.01M7 16h.01M11 16h.01M15 16h.01"></path></svg>',
        'cardTitle' => 'Form Edit Layout Peta',
        'cardDescription' => 'Sesuaikan data layout untuk peta parkir.',
        'action' => route('parking-maps.update', $item),
        'method' => 'PUT',
        'submitText' => 'Update'
    ])
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Layout <span class="text-red-500">*</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14M3 7a2 2 0 012-2h14m-4-2v4m0 0L14 5m1 2l1 1m-4 2h.01M7 12h.01M7 16h.01M11 16h.01M15 16h.01"></path></svg>
                </div>
                <input type="text" name="name" id="name" value="{{ old('name', $item->name) }}" required
                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-500 @enderror">
            </div>
            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">Kode Layout <span class="text-red-500">*</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <input type="text" name="code" id="code" value="{{ old('code', $item->code) }}" required
                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('code') border-red-500 @enderror">
            </div>
            @error('code')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="image_path" class="block text-sm font-semibold text-gray-700 mb-2">Path Gambar <span class="text-red-500">*</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h2l3 7 4-4 4 8 3-6h2"></path></svg>
                </div>
                <input type="text" name="image_path" id="image_path" value="{{ old('image_path', $item->image_path) }}" required
                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('image_path') border-red-500 @enderror">
            </div>
            <p class="mt-1 text-xs text-gray-500">Path relatif dari folder public. Contoh: <code>images/floor2.png</code>.</p>
            @error('image_path')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="width" class="block text-sm font-semibold text-gray-700 mb-2">Lebar Image (px) <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16v4H4zM4 16h16v4H4z"></path></svg>
                    </div>
                    <input type="number" name="width" id="width" value="{{ old('width', $item->width) }}" required min="1"
                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('width') border-red-500 @enderror">
                </div>
                @error('width')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="height" class="block text-sm font-semibold text-gray-700 mb-2">Tinggi Image (px) <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h4v16H4zM16 4h4v16h-4z"></path></svg>
                    </div>
                    <input type="number" name="height" id="height" value="{{ old('height', $item->height) }}" required min="1"
                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('height') border-red-500 @enderror">
                </div>
                @error('height')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="hidden" name="is_default" value="0">
                <input type="checkbox" name="is_default" value="1" {{ old('is_default', $item->is_default) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                <span class="text-sm font-semibold text-gray-700">Jadikan layout default</span>
            </label>
            @error('is_default')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
    @endcomponent

    {{-- Posisi kamera di peta --}}
    <div class="mt-8 bg-white shadow-lg rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900">Lokasi kamera di peta</h3>
                <p class="mt-0.5 text-xs text-gray-500">Klik pada gambar peta untuk mengisi koordinat kamera secara otomatis.</p>
            </div>
            <a href="{{ route('parking-maps.slots.index', $item) }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                Kelola slot →
            </a>
        </div>
        <div class="p-6 space-y-5">
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-800">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800">{{ session('error') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">
                <div class="lg:col-span-2 space-y-3">
                    @if($item->image_path)
                        <div class="overflow-auto rounded-xl border border-dashed border-gray-200 bg-slate-50/70 p-3">
                            <div
                                id="camera-map-clickable"
                                class="relative bg-center bg-no-repeat rounded-lg shadow-inner cursor-crosshair transition-transform duration-150 hover:scale-[1.01]"
                                style="
                                    width: {{ $item->width }}px;
                                    height: {{ $item->height }}px;
                                    background-image: url('{{ asset($item->image_path) }}');
                                    background-size: contain;
                                    background-color: #020617;
                                "
                            >
                                @if($item->mapCameras && $item->mapCameras->count())
                                    @foreach($item->mapCameras as $pmc)
                                        <div
                                            class="absolute -translate-x-1/2 -translate-y-1/2 flex items-center justify-center"
                                            style="left: {{ $pmc->x }}px; top: {{ $pmc->y }}px;"
                                        >
                                            <div class="h-5 w-5 rounded-full bg-emerald-500 shadow-lg shadow-emerald-500/40 ring-2 ring-white flex items-center justify-center text-[9px] font-bold text-white">
                                                C
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                <div
                                    id="camera-preview"
                                    class="absolute -translate-x-1/2 -translate-y-1/2 hidden"
                                >
                                    <div class="h-5 w-5 rounded-full bg-sky-500 shadow-lg shadow-sky-500/40 ring-2 ring-white flex items-center justify-center text-[9px] font-bold text-white">
                                        +
                                    </div>
                                </div>
                                <div class="pointer-events-none absolute inset-0 rounded-lg ring-1 ring-inset ring-white/5"></div>
                            </div>
                        </div>
                        <p class="text-[11px] text-gray-500">
                            Titik <span class="font-mono text-xs text-gray-700">(0, 0)</span> berada di kiri atas gambar. Klik peta untuk mengisi koordinat kamera secara otomatis.
                        </p>
                    @else
                        <p class="text-xs text-gray-500">
                            Setel terlebih dahulu gambar layout peta untuk mengaktifkan pemetaan kamera secara visual.
                        </p>
                    @endif
                </div>

                <div>
                    <form action="{{ route('parking-maps.cameras.store', $item) }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label for="cam_camera_id" class="block text-xs font-medium text-gray-600 mb-1">Kamera</label>
                            <select name="camera_id" id="cam_camera_id" required class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                                <option value="">-- Pilih kamera --</option>
                                @foreach($cameras as $c)
                                    <option value="{{ $c->id }}">{{ $c->nama }} ({{ $c->tipe }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="cam_x" class="block text-xs font-medium text-gray-600 mb-1">X (px)</label>
                                <input type="number" name="x" id="cam_x" value="100" min="0" class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                            </div>
                            <div>
                                <label for="cam_y" class="block text-xs font-medium text-gray-600 mb-1">Y (px)</label>
                                <input type="number" name="y" id="cam_y" value="100" min="0" class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                            </div>
                        </div>
                        <p class="text-[11px] text-gray-500">Klik peta di kiri untuk mengisi nilai X/Y, atau sesuaikan secara manual di sini.</p>
                        <button type="submit" class="w-full justify-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg flex items-center gap-2">
                            <span>Tambah posisi</span>
                        </button>
                    </form>
                </div>
            </div>

            @if($item->mapCameras && $item->mapCameras->count())
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Kamera</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">X</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600">Y</th>
                            <th class="px-4 py-2 text-right font-medium text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($item->mapCameras as $pmc)
                            <tr>
                                <td class="px-4 py-2">{{ $pmc->camera->nama ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $pmc->x }}</td>
                                <td class="px-4 py-2">{{ $pmc->y }}</td>
                                <td class="px-4 py-2 text-right">
                                    <form action="{{ route('parking-maps.cameras.destroy', [$item, $pmc]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus posisi kamera ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-500 text-sm">Belum ada kamera ditempatkan di peta ini. Klik peta di atas untuk memilih posisi, lalu simpan.</p>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const map = document.getElementById('camera-map-clickable');
            if (!map) return;

            const xInput = document.getElementById('cam_x');
            const yInput = document.getElementById('cam_y');
            const preview = document.getElementById('camera-preview');

            function updatePreview() {
                if (!preview || !map) return;
                const rect = map.getBoundingClientRect();
                const x = parseInt(xInput.value || '0', 10);
                const y = parseInt(yInput.value || '0', 10);

                preview.style.left = x + 'px';
                preview.style.top = y + 'px';
                preview.classList.remove('hidden');
            }

            map.addEventListener('click', function (e) {
                const rect = map.getBoundingClientRect();
                let x = e.clientX - rect.left;
                let y = e.clientY - rect.top;

                x = Math.round(Math.max(0, Math.min(x, rect.width)));
                y = Math.round(Math.max(0, Math.min(y, rect.height)));

                xInput.value = x;
                yInput.value = y;

                updatePreview();
            });

            [xInput, yInput].forEach(function (el) {
                el.addEventListener('input', updatePreview);
            });

            updatePreview();
        });
    </script>
@endpush

