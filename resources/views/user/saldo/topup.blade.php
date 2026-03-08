@extends('layouts.app')

@section('title', 'Top Up Saldo - NestonPay')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <div class="max-w-xl mx-auto">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('user.saldo.index') }}" class="w-10 h-10 bg-white border border-zinc-200 rounded-xl flex items-center justify-center text-zinc-400 hover:text-zinc-900 transition-all shadow-sm hover:border-zinc-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 tracking-tight">Top Up Saldo</h1>
                <p class="text-sm text-zinc-500">Isi ulang saldo NestonPay Anda untuk kemudahan pembayaran.</p>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] shadow-2xl shadow-zinc-100 border border-zinc-100 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('user.saldo.topup.store') }}" method="POST" class="space-y-8">
                    @csrf
                    <div>
                        <label for="amount" class="block text-[10px] font-bold text-zinc-400 uppercase tracking-[0.2em] mb-4">Nominal Top Up</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                <span class="text-zinc-400 font-bold text-xl">Rp</span>
                            </div>
                            <input type="number" name="amount" id="amount" required min="10000" step="1000"
                                   class="block w-full pl-16 pr-6 py-5 bg-zinc-50 border border-zinc-100 rounded-2xl text-3xl font-extrabold text-zinc-900 focus:ring-0 focus:border-zinc-900 transition-all placeholder:text-zinc-200"
                                   placeholder="0">
                        </div>
                        @error('amount')<p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                        <p class="mt-4 text-xs text-zinc-400 font-medium">Minimal top up sebesar <span class="text-zinc-900">Rp 10.000</span>.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" onclick="document.getElementById('amount').value = 20000" class="py-4 px-4 bg-zinc-50 hover:bg-zinc-900 hover:text-white rounded-2xl text-sm font-bold text-zinc-600 transition-all border border-zinc-100">
                            Rp 20.000
                        </button>
                        <button type="button" onclick="document.getElementById('amount').value = 50000" class="py-4 px-4 bg-zinc-50 hover:bg-zinc-900 hover:text-white rounded-2xl text-sm font-bold text-zinc-600 transition-all border border-zinc-100">
                            Rp 50.000
                        </button>
                        <button type="button" onclick="document.getElementById('amount').value = 100000" class="py-4 px-4 bg-zinc-50 hover:bg-zinc-900 hover:text-white rounded-2xl text-sm font-bold text-zinc-600 transition-all border border-zinc-100">
                            Rp 100.000
                        </button>
                        <button type="button" onclick="document.getElementById('amount').value = 200000" class="py-4 px-4 bg-zinc-50 hover:bg-zinc-900 hover:text-white rounded-2xl text-sm font-bold text-zinc-600 transition-all border border-zinc-100">
                            Rp 200.000
                        </button>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-zinc-900 hover:bg-zinc-800 text-white font-bold py-5 rounded-2xl shadow-xl shadow-zinc-200 transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Lanjutkan Pembayaran
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="bg-zinc-50/50 p-8 border-t border-zinc-100">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-white border border-zinc-100 rounded-xl flex items-center justify-center text-zinc-900 shrink-0 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-zinc-900 mb-1">Informasi Simulasi</h4>
                        <p class="text-xs text-zinc-500 leading-relaxed font-medium">Top up saat ini masih menggunakan simulasi manual. Saldo Anda akan langsung bertambah setelah menekan tombol "Lanjutkan".</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
