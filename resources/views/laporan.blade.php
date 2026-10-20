@extends('layouts.app')
@section('title', 'Laporan')
@section('content')

<div class="report-print">
<div class="flex flex-wrap items-end justify-between gap-3 mb-5">
    <div>
        <h1 class="font-display text-3xl">Laporan Penjualan</h1>
        <p class="report-period text-sm text-cream/50 mt-1">
            Periode {{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }}
            sampai {{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}
        </p>
    </div>
</div>

<form method="GET" class="flex flex-wrap gap-3 items-end mb-6 no-print">
    <div>
        <label class="text-xs text-cream/50">Dari</label>
        <input type="date" name="dari" value="{{ $dari }}" class="input">
    </div>
    <div>
        <label class="text-xs text-cream/50">Sampai</label>
        <input type="date" name="sampai" value="{{ $sampai }}" class="input">
    </div>
    <button class="btn-gold px-5 py-2.5 rounded-xl">Tampilkan</button>
    <button type="button" onclick="window.print()" class="btn-ghost px-5 py-2.5 rounded-xl">Cetak</button>
    <a href="{{ route('laporan.export', ['dari' => $dari, 'sampai' => $sampai]) }}"
class="btn-ghost px-5 py-2.5 rounded-xl">Export Excel</a>
</form>

@php
    $perHari = $transactions->groupBy(fn($t) => $t->created_at->format('d/m'))->map->sum('total')->reverse();
    $max = max($perHari->max() ?? 1, 1);
@endphp

<div class="report-summary grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
    <div class="glass rounded-2xl p-5">
        <div class="text-xs text-cream/50">Jumlah Transaksi</div>
        <div class="text-3xl font-bold gold-text">{{ $transactions->count() }}</div>
    </div>
    <div class="glass rounded-2xl p-5">
        <div class="text-xs text-cream/50">Total Penjualan</div>
        <div class="text-3xl font-bold gold-text">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
    </div>
</div>

@if($perHari->count())
    <div class="report-chart glass rounded-2xl p-5 mb-6">
        <h2 class="font-semibold mb-4">Penjualan per Hari</h2>
        <div class="flex items-end gap-2 h-40 overflow-x-auto">
            @foreach($perHari as $tgl => $tot)
                <div class="flex flex-col items-center justify-end h-full min-w-[36px] flex-1">
                    <div class="w-full rounded-t-md bg-gradient-to-t from-gold-600 to-gold-300"
                         style="height: {{ max(($tot / $max) * 100, 3) }}%"
                         title="Rp {{ number_format($tot, 0, ',', '.') }}"></div>
                    <div class="text-[10px] text-cream/50 mt-1">{{ $tgl }}</div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<section class="report-section mb-6">
<h2 class="font-semibold mb-2">Produk Terjual</h2>
<div class="glass rounded-2xl overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="text-left text-cream/50 text-xs uppercase">
            <tr><th class="p-4">Produk</th><th>Jumlah</th><th>Total</th></tr>
        </thead>
        <tbody>
        @foreach($produkTerjual as $p)
            <tr class="border-t border-white/5">
                <td class="p-4">{{ $p->nama_produk }}</td>
                <td>{{ $p->qty }}</td>
                <td>Rp {{ number_format($p->total, 0, ',', '.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</section>

<section class="report-section">
<h2 class="font-semibold mb-2">Daftar Transaksi</h2>
<div class="glass rounded-2xl overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="text-left text-cream/50 text-xs uppercase">
            <tr><th class="p-4">Tanggal</th><th>Kode</th><th>Kasir</th><th>Metode</th><th>Total</th></tr>
        </thead>
        <tbody>
        @foreach($transactions as $t)
            <tr class="border-t border-white/5">
                <td class="p-4">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $t->kode }}</td>
                <td>{{ $t->kasir->name }}</td>
                <td class="uppercase text-xs text-gold-400">{{ $t->metode }}</td>
                <td>Rp {{ number_format($t->total, 0, ',', '.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</section>
</div>
@endsection