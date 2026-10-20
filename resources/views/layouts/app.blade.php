<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Mas Bejo POS')</title>
    @include('partials.head')
</head>
<body>
    
<!-- tampilan menu sidebar -->
@php
    $isAdmin = auth()->user()->role === 'admin';
    $home = $isAdmin ? 'dashboard' : 'pos';

    // [nama route, label, ikon, khusus admin?]
    $menu = [
        ['dashboard',        'Dashboard', 'layout-dashboard', true],
        ['pos',              'Kasir',     'shopping-cart',    false],
        ['riwayat',          'Riwayat',   'receipt',          false],
        ['products.index',   'Produk',    'package',          true],
        ['categories.index', 'Kategori',  'tag',              true],
        ['users.index',      'Pengguna',  'users',            true],
        ['outlet.index',     'Outlet',    'store',            true],
        ['laporan',          'Laporan',   'bar-chart-3',      true],
    ];
@endphp
<!-- end tampilan menu sidebar -->


<!-- tampilan bar atas utuk di hp -->
{{-- Bar atas untuk HP --}}
<div class="lg:hidden no-print flex items-center justify-between px-4 py-3 bg-espresso-900 border-b border-gold-500/10">
    <span class="font-display text-xl gold-text">POS Digital</span>
    <button id="btn-menu" type="button" class="p-2" aria-label="Buka menu">
        <i data-lucide="menu" class="w-6 h-6"></i>
    </button>
</div>
<!-- end tampilan bar atas untuk di hp -->


{{-- Overlay HP --}}
<div id="overlay" class="no-print fixed inset-0 bg-black/60 z-30 hidden lg:hidden"></div>

<div class="flex">
    <aside id="sidebar" class="no-print fixed lg:sticky top-0 h-dvh w-64 -translate-x-full lg:translate-x-0 transition z-40 bg-espresso-900 border-r border-gold-500/10 flex flex-col p-5">
        <a href="{{ route($home) }}" class="block mb-6">
            <div class="font-display text-2xl gold-text">POS Digital</div>
            <div class="text-[11px] tracking-[.25em] text-cream/50 uppercase">By Aditya</div>
        </a>

        <nav class="space-y-1 flex-1 overflow-y-auto">
            @foreach($menu as [$r, $label, $icon, $adminOnly])
                @if(!$adminOnly || $isAdmin)
                    <a href="{{ route($r) }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition
                       {{ request()->routeIs(explode('.', $r)[0] . '*') ? 'bg-gold-500/15 text-gold-300' : 'text-cream/70 hover:bg-white/5' }}">
                        <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>{{ $label }}
                    </a>
                @endif
            @endforeach
        </nav>

        <div class="glass rounded-2xl p-3">
            <div class="text-sm font-medium">{{ auth()->user()->name }}</div>
            <div class="text-xs text-gold-400 mb-2 uppercase tracking-wider">{{ auth()->user()->role }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-ghost w-full py-1.5 rounded-lg text-sm flex items-center justify-center gap-2">
                    <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 min-w-0 p-4 lg:p-8">
        {{-- Pesan sukses --}}
        @if(session('success') || session('ok'))
            <div class="glass text-gold-300 p-3 rounded-xl mb-5 text-sm">
                {{ session('success') ?? session('ok') }}
            </div>
        @endif

        {{-- Pesan gagal --}}
        @if(session('error'))
            <div class="glass text-red-400 p-3 rounded-xl mb-5 text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Error validasi form --}}
        @if($errors->any())
            <div class="glass text-red-400 p-3 rounded-xl mb-5 text-sm">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>