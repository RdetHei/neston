        <!-- Sidebar -->
        <aside id="app-sidebar" class="sidebar-animate w-64 h-screen sticky top-0 bg-white border-r border-zinc-100 flex-shrink-0 hidden lg:flex flex-col z-50">
            <div class="sidebar-header h-16 border-b border-zinc-50 flex items-center justify-between px-3 lg:px-4">
                <div class="flex items-center gap-2 min-w-0 sidebar-header-brand">
                    <img src="{{ asset('images/neston.png') }}" alt="Parked" class="h-7 w-auto object-contain grayscale opacity-80 hover:grayscale-0 hover:opacity-100 transition-all">
                </div>
                <button id="sidebar-toggle" type="button"
                        class="sidebar-toggle-btn hidden lg:inline-flex items-center justify-center w-8 h-8 rounded-lg hover:bg-zinc-50 text-zinc-400 hover:text-zinc-600 shrink-0 transition-colors"
                        aria-label="Toggle sidebar">
                    <svg class="w-5 h-5 sidebar-toggle-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            @php $role = auth()->user()->role ?? 'user'; @endphp
            <nav class="flex-1 px-3 py-6 space-y-0.5 overflow-y-auto sidebar-nav">
                {{-- Dashboard: link sesuai role (sesuai SPK) --}}
                <a href="{{ $role === 'owner'
                            ? route('owner.dashboard')
                            : ($role === 'petugas'
                                ? route('petugas.dashboard')
                                : ($role === 'admin'
                                    ? route('dashboard')
                                    : route('user.dashboard'))) }}"
                   class="sidebar-item flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('dashboard', 'owner.dashboard', 'petugas.dashboard', 'user.dashboard') ? 'bg-zinc-900 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="sidebar-label">Dashboard</span>
                </a>

                {{-- PETUGAS: Transaksi (Catat Masuk, Parkir Aktif, Riwayat, Pembayaran) --}}
                @if($role === 'petugas')
                <a href="{{ route('transaksi.create-check-in') }}"
                   class="sidebar-item flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('transaksi.create-check-in') ? 'bg-zinc-900 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"></path></svg>
                    <span class="sidebar-label">Catat Masuk</span>
                </a>
                <a href="{{ route('transaksi.parkir.index') }}"
                   class="sidebar-item flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('transaksi.parkir.index') ? 'bg-zinc-900 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    <span class="sidebar-label">Parkir Aktif</span>
                </a>
                <a href="{{ route('parking.map.index') }}"
                   class="sidebar-item flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('parking.map.index') ? 'bg-zinc-900 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M3 5h2l3 7 4-4 4 8 3-6h2" />
                    </svg>
                    <span class="sidebar-label">Peta Parkir</span>
                </a>
                <a href="{{ route('anpr.index') }}"
                   class="sidebar-item flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('anpr.index') ? 'bg-zinc-900 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <span class="sidebar-label">Scan ANPR AI</span>
                </a>
                <a href="{{ route('transaksi.index') }}"
                   class="sidebar-item flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('transaksi.index', 'transaksi.show', 'transaksi.edit', 'transaksi.create') ? 'bg-zinc-900 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    <span class="sidebar-label">Riwayat Transaksi</span>
                </a>
                <a href="{{ route('payment.select-transaction') }}"
                   class="sidebar-item flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('payment.select-transaction', 'payment.create') ? 'bg-zinc-900 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="sidebar-label">Proses Pembayaran</span>
                </a>
                <a href="{{ route('payment.index') }}"
                   class="sidebar-item flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('payment.index') ? 'bg-zinc-900 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="sidebar-label">Riwayat Pembayaran</span>
                </a>
                @endif

                {{-- ADMIN: Riwayat Transaksi (lihat + cetak struk), Master Data, Log Aktivitas --}}
                @if($role === 'admin')
                <a href="{{ route('transaksi.index') }}"
                   class="sidebar-item flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('transaksi.index', 'transaksi.show', 'transaksi.edit', 'transaksi.create') ? 'bg-zinc-900 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span class="sidebar-label">Riwayat Transaksi</span>
                </a>
                <a href="{{ route('parking.map.index') }}"
                   class="sidebar-item flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('parking.map.index') ? 'bg-zinc-900 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M3 5h2l3 7 4-4 4 8 3-6h2" />
                    </svg>
                    <span class="sidebar-label">Peta Parkir</span>
                </a>
                <div class="pt-4 mt-4 border-t border-zinc-100">
                    <p class="sidebar-section-title text-[10px] font-bold text-zinc-400 uppercase px-3 mb-2 tracking-widest">Master Data</p>
                    <a href="{{ route('users.index') }}" class="sidebar-item flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-zinc-900 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="sidebar-label">Kelola User</span>
                    </a>
                    <a href="{{ route('tarif.index') }}" class="sidebar-item flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('tarif.*') ? 'bg-zinc-900 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="sidebar-label">Tarif Parkir</span>
                    </a>
                    <a href="{{ route('area-parkir.index') }}" class="sidebar-item flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('area-parkir.*') ? 'bg-zinc-900 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="sidebar-label">Area Parkir</span>
                    </a>
                    <a href="{{ route('parking-maps.index') }}" class="sidebar-item flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('parking-maps.*') ? 'bg-zinc-900 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M3 5h2l3 7 4-4 4 8 3-6h2" />
                        </svg>
                        <span class="sidebar-label">Layout Peta</span>
                    </a>
                    <a href="{{ route('kendaraan.index') }}" class="sidebar-item flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('kendaraan.*') ? 'bg-zinc-900 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                        <span class="sidebar-label">Kendaraan</span>
                    </a>
                    <a href="{{ route('kamera.index') }}" class="sidebar-item flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('kamera.*') ? 'bg-zinc-900 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <span class="sidebar-label">Kamera</span>
                    </a>
                    <a href="{{ route('log-aktivitas.index') }}" class="sidebar-item flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('log-aktivitas.*') ? 'bg-zinc-900 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="sidebar-label">Log Aktivitas</span>
                    </a>
                </div>
                @endif

                {{-- OWNER: Rekap transaksi sesuai waktu --}}
                @if($role === 'owner')
                <a href="{{ route('report.transaksi') }}"
                   class="sidebar-item flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('report.transaksi') ? 'bg-zinc-900 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span class="sidebar-label">Rekap Transaksi</span>
                </a>
                <a href="{{ route('report.pembayaran') }}"
                   class="sidebar-item flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-all duration-200 {{ request()->routeIs('report.pembayaran') ? 'bg-zinc-900 text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="sidebar-label">Rekap Pembayaran</span>
                </a>
                @endif
            </nav>

            <!-- NestonPay Quick View (For User) -->
            @if($role === 'user')
            <div class="px-4 mb-4 sidebar-account-wrap">
                <div class="bg-zinc-50 border border-zinc-100 rounded-2xl p-4 overflow-hidden relative group">
                    <div class="relative z-10">
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1 sidebar-label">NestonPay</p>
                        <p class="text-sm font-bold text-zinc-900 sidebar-label">Rp {{ number_format(Auth::user()->saldo, 0, ',', '.') }}</p>
                        <a href="{{ route('user.saldo.index') }}" class="mt-3 w-full py-1.5 bg-zinc-900 hover:bg-zinc-800 rounded-lg text-[10px] font-bold text-white text-center transition-all block sidebar-label">
                            TOP UP
                        </a>
                        <!-- Icon for collapsed state -->
                        <div class="hidden body[data-sidebar='collapsed'] &::block text-center py-1">
                             <svg class="w-5 h-5 mx-auto text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- User Profile Section -->
            <div class="relative p-4 border-t border-zinc-50 bg-white sidebar-account-wrap">
                <button type="button" id="sidebar-account-toggle" class="w-full flex items-center gap-3 rounded-xl hover:bg-zinc-50 transition-colors duration-200 text-left p-1.5 -m-1.5" aria-expanded="false" aria-haspopup="true" aria-label="Buka menu akun">
                    <div class="w-9 h-9 bg-zinc-100 rounded-full flex items-center justify-center text-zinc-600 font-bold text-xs shrink-0">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="sidebar-profile-details flex-1 min-w-0">
                        <p class="text-xs font-bold text-zinc-900 truncate">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="text-[10px] text-zinc-400 truncate">{{ auth()->user()->email ?? 'user@email.com' }}</p>
                    </div>
                    <svg class="w-4 h-4 text-zinc-300 shrink-0 sidebar-profile-details" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Popup card -->
                <div id="sidebar-account-popup" class="sidebar-account-popup hidden absolute bottom-full left-4 right-4 mb-2 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50 opacity-0 scale-95 origin-bottom">
                    <a href="{{ route('user.profile') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Profil Saya</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <script>
            (function () {
                var toggle = document.getElementById('sidebar-account-toggle');
                var popup = document.getElementById('sidebar-account-popup');
                var wrap = toggle && toggle.closest('.sidebar-account-wrap');

                if (!toggle || !popup || !wrap) return;

                function open() {
                    popup.classList.remove('hidden');
                    toggle.setAttribute('aria-expanded', 'true');
                    requestAnimationFrame(function () {
                        requestAnimationFrame(function () {
                            popup.classList.add('sidebar-account-popup-open');
                        });
                    });
                }

                function close() {
                    popup.classList.remove('sidebar-account-popup-open');
                    toggle.setAttribute('aria-expanded', 'false');
                    setTimeout(function () {
                        popup.classList.add('hidden');
                    }, 200);
                }

                function isOpen() {
                    return !popup.classList.contains('hidden');
                }

                toggle.addEventListener('click', function (e) {
                    e.stopPropagation();
                    if (isOpen()) close(); else open();
                });

                document.addEventListener('click', function () {
                    if (isOpen()) close();
                });

                popup.addEventListener('click', function (e) {
                    e.stopPropagation();
                });
            })();
        </script>
