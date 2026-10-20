@extends('layouts.app')
@section('title', 'Kasir')
@section('content')

<div id="pos-root" data-store-url="{{ route('pos.store') }}" class="pos-layout">

    {{-- Kiri: produk --}}
    <div class="pos-products">
        <h1 class="font-display text-3xl mb-4">Kasir</h1>

        <div class="mb-3 flex items-center gap-3">
            <input id="cari" oninput="filter()" placeholder="Cari produk..." class="input min-w-0 flex-1">
            <button type="button" id="toggle-cart" onclick="toggleKeranjang()"
                    class="no-print btn-gold flex shrink-0 items-center gap-2 rounded-xl px-4 py-2.5"
                    aria-label="Buka keranjang pesanan" aria-expanded="false">
                <i data-lucide="shopping-basket" class="h-5 w-5"></i>
                <span>Keranjang</span>
                <span id="cart-count" class="rounded-full bg-espresso-900/15 px-2 py-0.5 text-sm">0</span>
            </button>
        </div>

        <div class="flex flex-wrap gap-2 mb-4">
            <button data-k="" onclick="setKat('')" class="chip px-4 py-1.5 rounded-full text-sm btn-gold">Semua</button>
            @foreach($categories as $c)
                <button data-k="{{ $c->id }}" onclick="setKat('{{ $c->id }}')"
                        class="chip px-4 py-1.5 rounded-full text-sm btn-ghost">{{ $c->nama }}</button>
            @endforeach
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 2xl:grid-cols-5 gap-4">
            @foreach($products as $p)
                <button class="produk glass rounded-2xl overflow-hidden text-left hover:border-gold-500/60 active:scale-95 transition"
                        data-id="{{ $p->id }}" data-nama="{{ $p->nama }}" data-harga="{{ $p->harga }}"
                        data-kat="{{ $p->category_id }}" onclick="tambah(this)">
                    <div class="h-24 bg-espresso-800 flex items-center justify-center text-4xl overflow-hidden">
                        @if($p->gambar)
                            <img src="{{ asset('storage/'.$p->gambar) }}" class="h-full w-full object-cover">
                        @else 🥞 @endif
                    </div>
                    <div class="p-3">
                        <div class="text-sm font-medium leading-tight">{{ $p->nama }}</div>
                        <div class="gold-text font-bold text-sm mt-1">Rp {{ number_format($p->harga, 0, ',', '.') }}</div>
                    </div>
                </button>
            @endforeach
        </div>
    </div>

    <button type="button" id="cart-backdrop" class="pos-cart-backdrop" onclick="tutupKeranjang()" aria-label="Tutup keranjang"></button>
    <aside id="pos-cart-panel" class="pos-cart-panel glass rounded-2xl p-5 sm:p-6"
           aria-labelledby="pos-cart-title" aria-hidden="true">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h2 id="pos-cart-title" class="font-display text-2xl">Pesanan</h2>
                <p class="mt-1 text-xs text-cream/50">Periksa pesanan sebelum pembayaran.</p>
            </div>
            <button type="button" onclick="tutupKeranjang()" class="app-modal__close btn-ghost" aria-label="Tutup keranjang">×</button>
        </div>

        <div id="keranjang" class="mb-4 max-h-[30vh] space-y-3 overflow-y-auto pr-1"></div>

        <input id="catatan" placeholder="Catatan pesanan (opsional)" class="input mb-3 text-sm">

        <div class="grid grid-cols-3 gap-2">
            <button data-m="tunai" onclick="setMetode('tunai')" class="metode py-2 rounded-lg text-sm btn-gold">Tunai</button>
            <button data-m="qris" onclick="setMetode('qris')" class="metode py-2 rounded-lg text-sm btn-ghost">QRIS</button>
            <button data-m="transfer" onclick="setMetode('transfer')" class="metode py-2 rounded-lg text-sm btn-ghost">Transfer</button>
        </div>

        <div id="boxQris" class="hidden my-3 rounded-2xl border border-gold-500/30 p-4 text-center">
            <img src="{{ asset('images/qris.png') }}" alt="QRIS Martabak Mas Bejo" class="mx-auto w-48 max-w-full rounded-xl bg-white p-2">
            <p class="mt-3 text-xs text-cream/60">
             Scan QR di atas, lalu klik <b>Bayar &amp; Cetak Struk</b>
            </p>
        </div>

        <div class="border-t border-gold-500/15 pt-3 space-y-3">
            <div class="flex justify-between text-xl font-bold">
                <span>Total</span><span id="total" class="gold-text">Rp 0</span>
            </div>

            <div id="boxTunai" class="space-y-2">
                <input id="bayar" type="number" placeholder="Nominal bayar" oninput="hitung()" class="input">
                <div class="grid grid-cols-4 gap-2">
                    <button onclick="uangPas()" class="btn-ghost py-1.5 rounded-lg">Pas</button>
                    <button onclick="setBayar(20000)" class="btn-ghost py-1.5 rounded-lg">20rb</button>
                    <button onclick="setBayar(50000)" class="btn-ghost py-1.5 rounded-lg">50rb</button>
                    <button onclick="setBayar(100000)" class="btn-ghost py-1.5 rounded-lg">100rb</button>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-cream/60">Kembalian</span>
                    <span id="kembalian" class="font-semibold text-gold-300">Rp 0</span>
                </div>
            </div>

            <p id="pesan" class="text-red-400 text-sm"></p>
            <button onclick="bayarSekarang()" class="btn-gold w-full py-3 rounded-xl">Bayar &amp; Cetak Struk</button>
            <button onclick="kosongkan()" class="w-full text-sm text-cream/40 hover:text-cream/70">Kosongkan pesanan</button>
        </div>
    </aside>
</div>

<script src="{{ asset('js/pos.js') }}"></script>
@endsection