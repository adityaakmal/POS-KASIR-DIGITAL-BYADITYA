@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<h1 class="font-display text-3xl mb-1">Dashboard</h1>
<p class="text-cream/50 text-sm mb-6">{{ now()->translatedFormat('l, d F Y') }}</p>

<!-- dashboard admin dengan fitur untuk melihat omset harian - bulan, transaksi harian , transaksi rata rata  -->
@php
    $cards = [
        ['Omzet Hari Ini', 'Rp '.number_format($omzetHariIni,0,',','.'), 'wallet'],
        ['Transaksi Hari Ini', $trxHariIni, 'receipt'],
        ['Omzet Bulan Ini', 'Rp '.number_format($omzetBulan,0,',','.'), 'trending-up'],
        ['Rata-rata / Transaksi', 'Rp '.number_format($rataRata,0,',','.'), 'calculator'],
    ];
    $max = max($grafik->max('total'), 1);
@endphp

{{-- Kartu statistik --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
    @foreach($cards as [$label, $val, $icon])
        <div class="glass rounded-2xl p-5">
            <i data-lucide="{{ $icon }}" class="w-5 h-5 text-gold-400 mb-3"></i>
            <div class="text-xs text-cream/50">{{ $label }}</div>
            <div class="text-xl lg:text-2xl font-bold gold-text mt-1">{{ $val }}</div>
        </div>
    @endforeach
</div>

{{-- Grafik + produk terlaris --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    {{-- Grafik 7 hari --}}
    <div class="glass rounded-2xl p-5 lg:col-span-2">
        <h2 class="font-semibold mb-4">Penjualan 7 Hari Terakhir</h2>
        <div class="flex items-end gap-1.5 sm:gap-3 h-48">
            @foreach($grafik as $g)
                <div class="flex-1 flex flex-col items-center justify-end h-full">
                    <div class="text-[10px] text-cream/50 mb-1">{{ $g['total'] ? number_format($g['total']/1000, 0).'k' : '' }}</div>
                    <div class="w-full rounded-t-lg bg-gradient-to-t from-gold-600 to-gold-300"
                         style="height: {{ max(($g['total'] / $max) * 100, 2) }}%"></div>
                    <div class="text-[11px] text-cream/60 mt-2">{{ $g['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Produk terlaris --}}
    <div class="glass rounded-2xl p-5">
        <h2 class="font-semibold mb-4">Produk Terlaris</h2>
        @forelse($terlaris as $i => $t)
            <div class="flex items-center gap-3 py-2 border-b border-white/5 last:border-0">
                <span class="w-7 h-7 rounded-full bg-gold-500/15 text-gold-300 text-xs flex items-center justify-center">{{ $i + 1 }}</span>
                <span class="flex-1 text-sm">{{ $t->nama_produk }}</span>
                <span class="text-sm text-gold-300">{{ $t->qty }}x</span>
            </div>
        @empty
            <p class="text-sm text-cream/40">Belum ada data.</p>
        @endforelse
    </div>
</div>

{{-- Transaksi terbaru --}}
<div class="glass rounded-2xl p-5 mt-4">
    <div class="flex justify-between items-center mb-3">
        <h2 class="font-semibold">Transaksi Terbaru</h2>
        <a href="{{ route('riwayat') }}" class="text-sm text-gold-300">Lihat semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm whitespace-nowrap">
            @forelse($terbaru as $t)
                <tr class="border-t border-white/5">
                    <td class="py-2.5 pr-4">{{ $t->kode }}</td>
                    <td class="text-cream/60 pr-4">{{ $t->created_at->format('d/m H:i') }}</td>
                    <td class="uppercase text-xs text-gold-400 pr-4">{{ $t->metode }}</td>
                    <td class="text-right font-medium">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td class="py-4 text-cream/40">Belum ada transaksi.</td></tr>
            @endforelse
        </table>
    </div>
</div>
@endsection