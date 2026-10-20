@extends('layouts.app')
@section('title', 'Riwayat')
@section('content')

<h1 class="font-display text-3xl mb-5">Riwayat Transaksi</h1>

<div class="glass rounded-2xl overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="text-left text-cream/50 text-xs uppercase tracking-wider">
            <tr><th class="p-4">Kode</th><th>Tanggal</th><th>Kasir</th><th>Metode</th><th>Total</th><th></th></tr>
        </thead>
        <tbody>
        @foreach($transactions as $t)
            <tr class="border-t border-white/5 hover:bg-white/5">
                <td class="p-4 font-medium">{{ $t->kode }}</td>
                <td class="text-cream/70">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $t->kasir->name }}</td>
                <td><span class="text-xs uppercase px-2 py-1 rounded-full bg-gold-500/15 text-gold-300">{{ $t->metode }}</span></td>
                <td class="font-semibold">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                <td><a href="{{ route('struk', $t) }}" class="text-gold-300 hover:underline">Struk</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $transactions->links() }}</div>
@endsection