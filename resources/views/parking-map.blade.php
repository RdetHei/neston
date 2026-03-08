@extends('layouts.app')

@section('title', 'Peta Parkir')

@section('content')
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="max-w-7xl mx-auto">
            <div class="card-modern overflow-hidden">
                <div class="px-6 py-5 border-b border-zinc-100 bg-white flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-zinc-900 tracking-tight">Peta Interaktif Parkir</h2>
                        <p class="text-sm text-zinc-500 mt-0.5 font-medium">Pantau ketersediaan slot dan kamera pemantau secara real-time.</p>
                    </div>

                    <div class="flex items-center gap-3">
                        @if(!empty($maps) && $maps->count())
                            <form method="GET" action="{{ route('parking.map.index') }}" class="flex items-center gap-2">
                                <select id="map-select" name="map"
                                        onchange="this.form.submit()"
                                        class="block w-full md:w-48 px-4 py-2 bg-zinc-50 border border-zinc-200 rounded-xl text-sm font-bold text-zinc-700 focus:ring-0 focus:border-zinc-900 transition-all">
                                    @foreach($maps as $m)
                                        <option value="{{ $m->id }}" {{ $map && $map->id === $m->id ? 'selected' : '' }}>
                                            {{ $m->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        @endif
                        <button onclick="fetchData()" class="p-2.5 bg-zinc-50 hover:bg-zinc-900 hover:text-white text-zinc-600 rounded-xl transition-all border border-zinc-200" title="Refresh Data">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div id="parking-map-summary" class="px-6 py-4 bg-zinc-50/50 border-b border-zinc-100">
                    <div class="animate-pulse flex space-x-6">
                        <div class="h-4 bg-zinc-200 rounded-full w-32"></div>
                        <div class="h-4 bg-zinc-200 rounded-full w-32"></div>
                    </div>
                </div>

                <div class="p-6 bg-zinc-50/30">
                    <div class="relative group">
                        <div id="parking-map"
                             class="w-full rounded-[1.5rem] border-4 border-white shadow-2xl bg-zinc-200 overflow-hidden"
                             style="height: 600px;"
                             data-image-url="{{ $map ? asset($map->image_path) : asset('images/floor1.png') }}"
                             data-width="{{ $map ? $map->width : 1000 }}"
                             data-height="{{ $map ? $map->height : 800 }}"
                             data-map-id="{{ $map ? $map->id : '' }}"
                        >
                        </div>
                        
                        {{-- Legend Floating (Modernized) --}}
                        <div class="absolute bottom-6 right-6 bg-zinc-900/90 backdrop-blur-md px-5 py-4 rounded-2xl shadow-2xl border border-zinc-800 z-[1000] pointer-events-none">
                            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-[0.2em] mb-3">Legend Status</p>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <span class="w-3.5 h-3.5 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.4)]"></span>
                                    <span class="text-xs font-bold text-zinc-100">Tersedia</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="w-3.5 h-3.5 rounded-full bg-zinc-600"></span>
                                    <span class="text-xs font-bold text-zinc-100">Terisi</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="w-3.5 h-3.5 rounded-full bg-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.4)]"></span>
                                    <span class="text-xs font-bold text-zinc-100">Reserved</span>
                                </div>
                                <div class="flex items-center gap-3 pt-1">
                                    <div class="w-4 h-4 bg-white/20 rounded-md flex items-center justify-center">
                                        <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <span class="text-xs font-bold text-zinc-100">CCTV Cam</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .modern-popup .leaflet-popup-content-wrapper {
            border-radius: 16px;
            padding: 4px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0,0,0,0.05);
        }
        .modern-popup .leaflet-popup-content {
            margin: 12px;
        }
        .modern-popup .leaflet-popup-tip {
            box-shadow: none;
        }
        .parking-slot-rect {
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }
        .parking-map-camera-marker {
            transition: transform 0.2s ease-in-out;
        }
        .parking-map-camera-marker:hover {
            transform: scale(1.1);
            z-index: 1000 !important;
        }
    </style>

    {{-- Leaflet CSS & JS (CDN) --}}
    <link rel="stylesheet"
          href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>

    {{-- Script khusus peta parkir --}}
    <script src="{{ asset('js/parking-map.js') }}" defer></script>
@endsection

