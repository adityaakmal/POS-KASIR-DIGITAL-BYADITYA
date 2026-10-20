@extends('layouts.app')
@section('title', 'Outlet')
@section('content')

<div class="flex justify-between items-center mb-5">
    <h1 class="font-display text-3xl">Data Outlet</h1>
    <button type="button" data-open-dialog="outlet-edit-modal" class="btn-gold px-5 py-2.5 rounded-xl flex items-center gap-2">
        <i data-lucide="pencil" class="w-4 h-4"></i> Edit
    </button>
</div>

<dialog id="outlet-edit-modal" class="app-modal app-modal--wide" data-app-dialog
        data-auto-open="{{ $errors->any() ? 'true' : 'false' }}">
    <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-display text-2xl">Edit Outlet</h2>
            <button type="button" data-close-dialog class="app-modal__close btn-ghost" aria-label="Tutup form">×</button>
        </div>

        @include('outlet._form', ['inModal' => true])
    </div>
</dialog>

<div class="glass rounded-2xl overflow-hidden max-w-5xl grid grid-cols-1 md:grid-cols-2">
    <dl class="order-2 md:order-1 p-6 md:p-8 space-y-5 text-sm">
        <div><dt class="text-cream/50 text-xs uppercase tracking-wider">Nama</dt><dd class="mt-1">{{ $outlet->nama }}</dd></div>
        <div><dt class="text-cream/50 text-xs uppercase tracking-wider">Alamat</dt><dd class="mt-1">{{ $outlet->alamat }}</dd></div>
        <div><dt class="text-cream/50 text-xs uppercase tracking-wider">Deskripsi</dt><dd class="mt-1">{{ $outlet->deskripsi ?: '-' }}</dd></div>
        <div><dt class="text-cream/50 text-xs uppercase tracking-wider">Telepon</dt><dd class="mt-1">{{ $outlet->telepon ?: '-' }}</dd></div>
        <div><dt class="text-cream/50 text-xs uppercase tracking-wider">Jam Buka</dt><dd class="mt-1">{{ $outlet->jam_buka ?: '-' }}</dd></div>
    </dl>

    <div class="order-1 md:order-2 aspect-[16/9] md:aspect-auto md:min-h-[28rem] bg-espresso-800 flex items-center justify-center">
        @if($outlet->gambar)
            <img src="{{ asset('storage/'.$outlet->gambar) }}" alt="Foto {{ $outlet->nama }}"
                 loading="lazy" class="h-full w-full object-cover">
        @else
            <div class="flex flex-col items-center gap-2 text-cream/40">
                <i data-lucide="store" class="w-10 h-10"></i>
                <span class="text-sm">Belum ada foto outlet</span>
            </div>
        @endif
    </div>
</div>
@endsection