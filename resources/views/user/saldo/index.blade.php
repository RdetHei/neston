@extends('layouts.app')

@section('title', 'NestonPay - Dompet Digital')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Saldo Card (Modernized Dark Card) -->
        <div class="bg-zinc-900 rounded-[2rem] p-8 text-white shadow-2xl shadow-zinc-200 relative overflow-hidden mb-8 group">
            <div class="absolute -right-10 -top-10 w-48 h-48 bg-zinc-800 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-700"></div>
            <div class="absolute right-20 bottom-0 w-32 h-32 bg-zinc-800 rounded-full opacity-30 group-hover:-translate-y-4 transition-transform duration-700"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-zinc-800 flex items-center justify-center">
                        <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-zinc-400 uppercase tracking-widest">NestonPay Balance</span>
                </div>
                <h2 class="text-5xl font-extrabold tracking-tight mb-8">Rp {{ number_format($user->saldo, 0, ',', '.') }}</h2>
                
                <div class="flex gap-3">
                    <a href="{{ route('user.saldo.topup') }}" class="px-8 py-4 bg-white text-zinc-900 rounded-2xl text-sm font-bold shadow-lg hover:bg-zinc-100 transition-all active:scale-95 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Top Up Saldo
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-zinc-900 border border-zinc-800 rounded-2xl p-4 flex items-center gap-3">
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-zinc-900 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <p class="text-sm font-bold text-white">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Transaction History -->
        <div class="card-modern overflow-hidden">
            <div class="px-6 py-5 border-b border-zinc-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-zinc-900">Riwayat Transaksi</h3>
            </div>
            
            <div class="divide-y divide-zinc-50">
                @forelse($histories as $history)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-zinc-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ $history->amount > 0 ? 'bg-zinc-100 text-zinc-900' : 'bg-zinc-50 text-zinc-400' }}">
                                @if($history->type === 'topup')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                @elseif($history->type === 'payment')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3"></path></svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-bold text-zinc-900">{{ $history->description }}</p>
                                <p class="text-xs text-zinc-400">{{ $history->created_at->translatedFormat('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold {{ $history->amount > 0 ? 'text-zinc-900' : 'text-zinc-500' }}">
                                {{ $history->amount > 0 ? '+' : '' }} Rp {{ number_format($history->amount, 0, ',', '.') }}
                            </p>
                            <p class="text-[10px] font-bold text-zinc-300 uppercase tracking-widest">{{ $history->type }}</p>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="w-16 h-16 bg-zinc-50 rounded-full flex items-center justify-center mx-auto mb-4 text-zinc-200">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-zinc-400 text-sm italic">Belum ada riwayat transaksi.</p>
                    </div>
                @endforelse
            </div>

            @if($histories->hasPages())
                <div class="px-6 py-4 border-t border-gray-50 bg-gray-50/50">
                    {{ $histories->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
