@extends('layouts.app')
@section('title', 'Struk ' . $trx->kode)
@section('content')

<style>
    @page {
        size: 80mm auto;
        margin: 0;
    }

    @media print {
        /* Sembunyikan semua elemen halaman (navbar, sidebar, latar, tombol) */
        body * {
            visibility: hidden !important;
        }

        html, body {
            background: #fff !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Tampilkan hanya struk */
        #struk, #struk * {
            visibility: visible !important;
        }

        #struk {
            position: absolute;
            left: 0;
            top: 0;
            width: 72mm;              /* area cetak efektif kertas 80mm */
            margin: 0 !important;
            padding: 3mm !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            background: #fff !important;
            color: #000 !important;
            font-size: 12px;
        }

        .no-print {
            display: none !important;
        }
    }
</style>

<div class="max-w-sm mx-auto">
    <div id="struk" class="bg-[#fffaf0] text-stone-900 rounded-2xl p-6 text-sm shadow-2xl font-mono">
        <div class="text-center mb-3">
            <div class="font-bold text-lg tracking-wide">MARTABAK MAS BEJO</div>
            <div class="text-xs">Cabang Pondok Ciptamas</div>
            <div class="text-xs">Jl. Kerkof</div>
        </div>

        <div class="border-y border-dashed border-stone-400 py-2 my-2 text-xs space-y-0.5">
            <div class="flex justify-between"><span>No</span><span>{{ $trx->kode }}</span></div>
            <div class="flex justify-between"><span>Tanggal</span><span>{{ $trx->created_at->format('d/m/Y H:i') }}</span></div>
            <div class="flex justify-between"><span>Kasir</span><span>{{ $trx->kasir->name }}</span></div>
            <div class="flex justify-between"><span>Metode</span><span class="uppercase">{{ $trx->metode }}</span></div>
        </div>

        @foreach($trx->items as $i)
            <div class="mb-1">
                <div>{{ $i->nama_produk }}</div>
                <div class="flex justify-between text-xs">
                    <span>{{ $i->qty }} x {{ number_format($i->harga, 0, ',', '.') }}</span>
                    <span>{{ number_format($i->subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
        @endforeach

        <div class="border-t border-dashed border-stone-400 mt-2 pt-2 space-y-1">
            <div class="flex justify-between font-bold"><span>TOTAL</span><span>Rp {{ number_format($trx->total, 0, ',', '.') }}</span></div>
            <div class="flex justify-between"><span>Bayar</span><span>Rp {{ number_format($trx->bayar, 0, ',', '.') }}</span></div>
            <div class="flex justify-between"><span>Kembalian</span><span>Rp {{ number_format($trx->kembalian, 0, ',', '.') }}</span></div>
        </div>

        @if($trx->catatan)
            <div class="mt-3 text-xs">Catatan: {{ $trx->catatan }}</div>
        @endif

        <p class="text-center mt-5 text-xs">Terima kasih, selamat menikmati!</p>
    </div>

    <div class="mt-4 flex gap-2 no-print">
        <button onclick="window.print()" class="btn-gold flex-1 py-2.5 rounded-xl flex items-center justify-center gap-2">
            <i data-lucide="printer" class="w-4 h-4"></i> Cetak
        </button>
        <a href="{{ route('pos') }}" class="btn-ghost flex-1 py-2.5 rounded-xl text-center">Transaksi Baru</a>
    </div>  
</div>
@endsection