@extends('layouts.app')

@section('content')
    <div class="p-8">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-zinc-900">Dashboard</h1>
                <p class="text-sm text-zinc-500 mt-1.5">Ringkasan aktivitas operasional hari ini.</p>
            </div>
            <div class="flex gap-2">
                <div class="inline-flex items-center gap-2.5 px-4 py-2 bg-white border border-zinc-100 rounded-xl text-xs font-bold text-zinc-500 shadow-sm">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                    {{ now()->translatedFormat('d F Y') }}
                </div>
            </div>
        </div>

        <!-- Statistik Utama -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="card-modern">
                <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-2">Revenue Today</p>
                <p class="stat-card-value">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</p>
                <div class="mt-4 flex items-center gap-1.5">
                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Live</span>
                </div>
            </div>

            <div class="card-modern">
                <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-2">Transactions</p>
                <p class="stat-card-value">{{ $transaksiHariIni }}</p>
                <p class="text-[10px] text-zinc-500 mt-4">Total transaksi hari ini</p>
            </div>

            <div class="card-modern">
                <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-2">Active Parking</p>
                <p class="stat-card-value">{{ $transaksiAktif }}</p>
                <p class="text-[10px] text-zinc-500 mt-4">Kendaraan di dalam area</p>
            </div>

            <div class="card-modern">
                <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-2">Registered Users</p>
                <p class="stat-card-value">{{ $totalUser }}</p>
                <p class="text-[10px] text-zinc-500 mt-4">Pertumbuhan komunitas</p>
            </div>
        </div>

        <!-- Grafik & Visualisasi -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
            <div class="lg:col-span-2 card-modern">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-base font-bold text-zinc-900">Tren Pendapatan</h2>
                        <p class="text-xs text-zinc-400 mt-1">Performa 7 hari terakhir</p>
                    </div>
                </div>
                <div class="h-[300px]">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="card-modern">
                <h2 class="text-base font-bold text-zinc-900 mb-8">Distribusi Kendaraan</h2>
                <div class="h-[240px] flex items-center justify-center">
                    <canvas id="vehicleChart"></canvas>
                </div>
                <div class="mt-8 space-y-4">
                    @php 
                        $totalV = array_sum($grafikKendaraan['data']);
                    @endphp
                    @foreach($grafikKendaraan['labels'] as $index => $label)
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full {{ $index == 0 ? 'bg-zinc-900' : 'bg-zinc-300' }}"></span>
                                <span class="text-zinc-500 font-medium">{{ $label }}</span>
                            </div>
                            <span class="font-bold text-zinc-900">{{ $grafikKendaraan['data'][$index] }} ({{ $totalV > 0 ? round($grafikKendaraan['data'][$index] / $totalV * 100) : 0 }}%)</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Advanced Analytics Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
            <div class="lg:col-span-2 card-modern">
                <div class="mb-8">
                    <h2 class="text-base font-bold text-zinc-900">Analisis Jam Sibuk</h2>
                    <p class="text-xs text-zinc-400 mt-1">Distribusi pendapatan berdasarkan waktu</p>
                </div>
                <div class="h-[250px]">
                    <canvas id="hourHeatmapChart"></canvas>
                </div>
            </div>

            <div class="card-modern">
                <h2 class="text-base font-bold text-zinc-900 mb-8">Area Terpopuler</h2>
                <div class="space-y-7">
                    @foreach($topAreas as $index => $item)
                        @php
                            $maxTotal = $topAreas->max('total');
                            $width = $maxTotal > 0 ? ($item->total / $maxTotal * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between items-center mb-2.5">
                                <span class="text-xs font-bold text-zinc-700">{{ $item->area->nama_area ?? 'Unknown' }}</span>
                                <span class="text-[10px] font-bold text-zinc-400">{{ $item->total }} Trx</span>
                            </div>
                            <div class="w-full bg-zinc-50 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-zinc-900 h-full rounded-full" style="width: {{ $width }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Monitoring Area -->
            <div class="lg:col-span-1 space-y-6">
                <div class="card-modern !p-0 overflow-hidden">
                    <div class="px-6 py-4 border-b border-zinc-50 bg-zinc-50/30 flex items-center justify-between">
                        <h2 class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Status Area</h2>
                        <a href="{{ route('area-parkir.index') }}" class="text-[10px] font-bold text-zinc-900 hover:underline">Detail</a>
                    </div>
                    <div class="p-6 space-y-6">
                        @foreach($areaParkir as $area)
                            @php
                                $percent = $area->kapasitas > 0 ? ($area->terisi / $area->kapasitas * 100) : 0;
                            @endphp
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs font-bold text-zinc-700">{{ $area->nama_area }}</span>
                                    <span class="text-[10px] font-bold text-zinc-400">
                                        {{ $area->terisi }}/{{ $area->kapasitas }}
                                    </span>
                                </div>
                                <div class="w-full bg-zinc-50 rounded-full h-1">
                                    <div class="bg-zinc-900 h-1 rounded-full" style="width: {{ min($percent, 100) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Aktivitas Terbaru -->
            <div class="lg:col-span-2">
                <div class="card-modern !p-0 overflow-hidden">
                    <div class="px-6 py-4 border-b border-zinc-50 bg-zinc-50/30 flex items-center justify-between">
                        <h2 class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Aktivitas Terbaru</h2>
                        <a href="{{ route('transaksi.index') }}" class="text-[10px] font-bold text-zinc-900 hover:underline">Lihat Semua</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <tbody class="divide-y divide-zinc-50">
                                @forelse($aktivitasTerbaru as $trx)
                                    <tr class="hover:bg-zinc-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-xl bg-zinc-50 border border-zinc-100 flex items-center justify-center font-bold text-zinc-400 text-[10px]">
                                                    {{ substr($trx->kendaraan->plat_nomor ?? '-', 0, 2) }}
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-zinc-900">{{ $trx->kendaraan->plat_nomor ?? '-' }}</p>
                                                    <p class="text-[10px] text-zinc-400 uppercase font-medium">{{ $trx->kendaraan->jenis_kendaraan ?? 'Kendaraan' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-xs text-zinc-500 font-medium">{{ $trx->area->nama_area ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <p class="text-[10px] font-bold text-zinc-900">{{ $trx->waktu_masuk->diffForHumans() }}</p>
                                            <p class="text-[10px] text-zinc-400">{{ $trx->waktu_masuk->format('H:i') }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold {{ $trx->status === 'masuk' ? 'bg-zinc-900 text-white' : 'bg-zinc-100 text-zinc-500' }}">
                                                {{ ucfirst($trx->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-20 text-center">
                                            <p class="text-zinc-400 text-xs italic">Belum ada aktivitas hari ini.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.color = '#71717a';

            // Revenue Chart
            const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctxRevenue, {
                type: 'line',
                data: {
                    labels: @json($grafikPendapatan['labels']),
                    datasets: [{
                        data: @json($grafikPendapatan['data']),
                        borderColor: '#18181b',
                        backgroundColor: 'rgba(24, 24, 27, 0.02)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#18181b',
                        pointBorderWidth: 2,
                        pointRadius: 0,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#18181b',
                            padding: 12,
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 12 },
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return ' Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f4f4f5' },
                            ticks: {
                                font: { size: 10 },
                                callback: function(value) {
                                    if (value >= 1000000) return (value / 1000000) + 'M';
                                    if (value >= 1000) return (value / 1000) + 'K';
                                    return value;
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });

            // Vehicle Chart
            const ctxVehicle = document.getElementById('vehicleChart').getContext('2d');
            new Chart(ctxVehicle, {
                type: 'doughnut',
                data: {
                    labels: @json($grafikKendaraan['labels']),
                    datasets: [{
                        data: @json($grafikKendaraan['data']),
                        backgroundColor: ['#18181b', '#e4e4e7'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '80%',
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // Hour Heatmap Chart
            const ctxHour = document.getElementById('hourHeatmapChart').getContext('2d');
            new Chart(ctxHour, {
                type: 'bar',
                data: {
                    labels: Array.from({length: 24}, (_, i) => i + ':00'),
                    datasets: [{
                        data: @json(array_values($revenueByHour)),
                        backgroundColor: '#18181b',
                        borderRadius: 4,
                        borderWidth: 0,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#18181b',
                            callbacks: {
                                label: function(context) {
                                    return ' Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            grid: { display: false },
                            ticks: { display: false }
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { font: { size: 9 } }
                        }
                    }
                }
            });
        });
    </script>
@endpush
