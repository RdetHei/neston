@extends('layouts.app')

@section('title', 'ANPR Scan - Sistem Parkir Neston')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Camera & Scanner Section -->
            <div class="flex-1">
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-zinc-100 relative group">
                    <div class="p-6 border-b border-zinc-50 flex items-center justify-between bg-zinc-900 text-white">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-zinc-800 rounded-xl flex items-center justify-center text-zinc-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold tracking-tight">Real-time ANPR Scanner</h2>
                                <p class="text-xs text-zinc-400 uppercase tracking-widest font-black">Scanning Every 2s</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span id="scan-status" class="flex h-3 w-3 rounded-full bg-red-500 animate-pulse"></span>
                            <span id="status-text" class="text-xs font-bold uppercase tracking-widest text-zinc-400">Idle</span>
                        </div>
                    </div>

                    <div class="relative bg-black aspect-video flex items-center justify-center">
                        <video id="video" autoplay playsinline muted class="w-full h-full object-cover"></video>
                        <img id="upload-preview" class="w-full h-full object-contain hidden" alt="Upload Preview">
                        <canvas id="overlay" class="absolute inset-0 w-full h-full pointer-events-none"></canvas>
                        
                        <!-- Floating Vehicle Info -->
                        <div id="scanner-vehicle-info" class="absolute top-6 left-6 bg-zinc-900/90 backdrop-blur-md text-white px-4 py-2 rounded-xl border border-white/10 shadow-2xl z-10 hidden animate-in fade-in zoom-in duration-300">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                                <div>
                                    <p id="scanner-plate" class="text-lg font-black tracking-tight leading-none">-</p>
                                    <p id="scanner-vehicle" class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest mt-1">-</p>
                                </div>
                            </div>
                        </div>

                        <div id="loading-overlay" class="absolute inset-0 bg-black/50 flex items-center justify-center z-20 hidden">
                            <div class="text-center">
                                <div class="w-12 h-12 border-4 border-zinc-100 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                                <p class="text-white text-xs font-bold uppercase tracking-[0.2em]">Processing OCR...</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 bg-zinc-50 border-t border-zinc-100 flex flex-wrap gap-4 items-center justify-between">
                        <div class="flex gap-3">
                            <button id="start-scan" class="px-6 py-3 bg-zinc-900 text-white rounded-2xl text-sm font-bold shadow-xl shadow-zinc-200 hover:bg-zinc-800 transition-all flex items-center gap-2 active:scale-95">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
                                Start Scan
                            </button>
                            <button id="stop-scan" class="px-6 py-3 bg-white text-zinc-400 border border-zinc-200 rounded-2xl text-sm font-bold hover:bg-zinc-50 transition-all flex items-center gap-2 active:scale-95 hidden">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path></svg>
                                Stop
                            </button>
                            
                            <input type="file" id="file-input" class="hidden" accept="image/*">
                            <button id="upload-btn" class="px-6 py-3 bg-white text-zinc-900 border border-zinc-200 rounded-2xl text-sm font-bold hover:bg-zinc-50 transition-all flex items-center gap-2 active:scale-95">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                Upload Foto
                            </button>
                        </div>
                        <div class="flex items-center gap-4 text-zinc-400">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                <span class="text-[10px] font-bold uppercase tracking-widest">Confidence > 80% Required</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Sidebar -->
            <div class="w-full lg:w-[380px] space-y-6">
                <div id="result-card" class="card-modern p-8 bg-white hidden animate-in slide-in-from-right duration-500">
                    <h3 class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.3em] mb-6">Last Scan Result</h3>
                    
                    <div class="space-y-6">
                        <div class="text-center p-6 bg-zinc-900 rounded-[2rem] text-white shadow-2xl shadow-zinc-200">
                            <p id="result-plate" class="text-4xl font-black tracking-tighter mb-1">-------</p>
                            <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest">Plate Number</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-zinc-50 rounded-2xl border border-zinc-100">
                                <p id="result-confidence" class="text-lg font-black text-zinc-900">0%</p>
                                <p class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest">Confidence</p>
                            </div>
                            <div class="p-4 bg-zinc-50 rounded-2xl border border-zinc-100">
                                <p id="result-action" class="text-lg font-black text-zinc-900">N/A</p>
                                <p class="text-[8px] font-bold text-zinc-400 uppercase tracking-widest">Action</p>
                            </div>
                        </div>

                        <div class="p-6 bg-zinc-50 rounded-2xl border border-zinc-100 space-y-4">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-zinc-400 font-bold uppercase">Type & Color</span>
                                <span id="result-vehicle" class="text-zinc-900 font-black">-</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-zinc-400 font-bold uppercase">Time</span>
                                <span id="result-time" class="text-zinc-900 font-black">-</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-zinc-400 font-bold uppercase">Status</span>
                                <span id="result-status" class="text-zinc-900 font-black">-</span>
                            </div>
                        </div>

                        <div id="result-image-container" class="rounded-2xl overflow-hidden border border-zinc-200 bg-zinc-100">
                            <img id="result-image" src="" alt="Scan Thumbnail" class="w-full h-auto object-cover opacity-80">
                        </div>
                    </div>
                </div>

                <div id="no-result" class="card-modern p-12 bg-white text-center">
                    <div class="w-16 h-16 bg-zinc-50 rounded-2xl flex items-center justify-center mx-auto mb-6 text-zinc-200">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                    </div>
                    <p class="text-sm font-bold text-zinc-400">Belum ada scan aktif.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/anpr.js') }}"></script>
@endpush
