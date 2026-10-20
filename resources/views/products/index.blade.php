@extends('layouts.app')
@section('title', 'Produk')
@section('content')

@php
    $qrisFile = collect(\Illuminate\Support\Facades\Storage::disk('public')->files('qris'))->first();
@endphp

<div class="flex justify-between items-center mb-5">
    <h1 class="font-display text-3xl">Kelola Produk</h1>
    <div class="flex items-center gap-2">
        <button type="button" data-open-dialog="qris-modal" class="btn-ghost px-5 py-2.5 rounded-xl flex items-center gap-2">
            <i data-lucide="qr-code" class="w-4 h-4"></i> QRIS
        </button>
        <button type="button" data-open-dialog="product-create-modal" class="btn-gold px-5 py-2.5 rounded-xl flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah
        </button>
    </div>
</div>

{{-- Hapus blok ini jika layouts/app.blade.php sudah menampilkan session('ok') --}}
@if(session('ok'))
    <div class="bg-green-500/15 text-green-300 p-3 rounded-xl text-sm mb-4">{{ session('ok') }}</div>
@endif

{{-- Popup QRIS --}}
<dialog id="qris-modal" class="app-modal" data-app-dialog
        data-auto-open="{{ old('_modal_id') === 'qris-modal' ? 'true' : 'false' }}">
    <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-display text-2xl">Gambar QRIS</h2>
            <button type="button" data-close-dialog class="app-modal__close btn-ghost" aria-label="Tutup form">×</button>
        </div>

        @if($qrisFile)
            <img src="{{ asset('storage/'.$qrisFile) }}" alt="QRIS"
                 class="mx-auto mb-4 w-48 max-w-full rounded-xl bg-white p-2">
        @else
            <p class="mb-4 text-center text-sm text-cream/50">Belum ada gambar QRIS.</p>
        @endif

        <form method="POST" action="{{ route('qris.update') }}" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <input type="hidden" name="_modal_id" value="qris-modal">
            <input type="file" name="qris" accept="image/*" class="input" required>
            @error('qris')
                <p class="text-sm text-red-400">{{ $message }}</p>
            @enderror

            <div class="flex gap-2 pt-2">
                <button type="button" data-close-dialog class="btn-ghost flex-1 py-2.5 rounded-xl">Batal</button>
                <button type="submit" class="btn-gold flex-1 py-2.5 rounded-xl">Simpan</button>
            </div>
        </form>

        @if($qrisFile)
            <form method="POST" action="{{ route('qris.destroy') }}" class="mt-3 text-center"
                  onsubmit="return confirm('Hapus gambar QRIS?')">
                @csrf @method('DELETE')
                <button class="text-sm text-red-400">Hapus gambar</button>
            </form>
        @endif
    </div>
</dialog>

{{-- Popup Tambah Produk --}}
<dialog id="product-create-modal" class="app-modal app-modal--wide" data-app-dialog data-auto-open="{{ $errors->any() && !old('_modal_id') ? 'true' : 'false' }}">
    <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-display text-2xl">Tambah Produk</h2>
            <button type="button" data-close-dialog class="app-modal__close btn-ghost" aria-label="Tutup form">×</button>
        </div>

        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data"
              data-product-form class="space-y-4">
            @csrf
            @include('products._fields')

            <div class="flex gap-2 pt-2">
                <button type="button" data-close-dialog class="btn-ghost flex-1 py-2.5 rounded-xl">Batal</button>
                <button type="submit" class="btn-gold flex-1 py-2.5 rounded-xl">Simpan</button>
            </div>
        </form>
    </div>
</dialog>

{{-- Tabel Produk --}}
<div class="glass rounded-2xl overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="text-left text-cream/50 text-xs uppercase">
            <tr><th class="p-4">Produk</th><th>Kategori</th><th>Harga</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        @forelse($products as $p)
            <tr class="border-t border-white/5">
                <td class="p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 shrink-0 rounded-lg bg-espresso-800 overflow-hidden flex items-center justify-center">
                            @if($p->gambar)
                                <img src="{{ asset('storage/'.$p->gambar) }}" alt="{{ $p->nama }}"
                                     loading="lazy" decoding="async"
                                     class="w-full h-full object-cover">
                            @else 🥞 @endif
                        </div>
                        {{ $p->nama }}
                    </div>
                </td>
                <td>{{ $p->category?->nama ?? '-' }}</td>
                <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                <td>
                    <span class="text-xs px-2 py-1 rounded-full {{ $p->aktif ? 'bg-green-500/15 text-green-300' : 'bg-red-500/15 text-red-300' }}">
                        {{ $p->aktif ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td>
                    <div class="flex items-center gap-3">
                        <button type="button" data-open-dialog="product-edit-modal-{{ $p->id }}" class="text-gold-300">Edit</button>
                        <form method="POST" action="{{ route('products.destroy', $p) }}"
                              onsubmit="return confirm('Hapus produk ini beserta gambarnya?')">
                            @csrf @method('DELETE')
                            <button class="text-red-400">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="p-8 text-center text-cream/50">Belum ada produk.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $products->links() }}
</div>

{{-- Popup Edit Produk --}}
@foreach($products as $p)
    <dialog id="product-edit-modal-{{ $p->id }}" class="app-modal app-modal--wide" data-app-dialog
            data-auto-open="{{ old('_modal_id') === 'product-edit-modal-'.$p->id ? 'true' : 'false' }}">
        <div class="glass rounded-2xl p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-display text-2xl">Edit Produk</h2>
                <button type="button" data-close-dialog class="app-modal__close btn-ghost" aria-label="Tutup form">×</button>
            </div>

            <form method="POST" action="{{ route('products.update', $p) }}" enctype="multipart/form-data"
                  data-product-form class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="_modal_id" value="product-edit-modal-{{ $p->id }}">
                @include('products._fields', ['product' => $p])

                <div class="flex gap-2 pt-2">
                    <button type="button" data-close-dialog class="btn-ghost flex-1 py-2.5 rounded-xl">Batal</button>
                    <button type="submit" class="btn-gold flex-1 py-2.5 rounded-xl">Simpan</button>
                </div>
            </form>
        </div>
    </dialog>
@endforeach
@endsection