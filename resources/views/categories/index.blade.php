@extends('layouts.app')
@section('title', 'Kategori')
@section('content')

<div class="flex justify-between items-center mb-5">
    <h1 class="font-display text-3xl">Kelola Kategori</h1>
    <button type="button" data-open-dialog="category-create-modal" class="btn-gold px-5 py-2.5 rounded-xl flex items-center gap-2">
        <i data-lucide="plus" class="w-4 h-4"></i> Tambah
    </button>
</div>

<dialog id="category-create-modal" class="app-modal" data-app-dialog data-auto-open="{{ $errors->any() && !old('_modal_id') ? 'true' : 'false' }}">
    <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 id="category-modal-title" class="font-display text-2xl">Tambah Kategori</h2>
            <button type="button" data-close-dialog class="app-modal__close btn-ghost" aria-label="Tutup form">×</button>
        </div>

        <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
            @csrf
            <div>
                <label for="category-name" class="block text-sm text-cream/60 mb-1">Nama kategori</label>
                <input type="text" id="category-name" name="nama" maxlength="50" required autofocus
                       value="{{ old('nama') }}"
                       placeholder="Contoh: Martabak Manis"
                       class="input w-full">
            </div>

            <div class="flex gap-2 pt-2">
                <button type="button" data-close-dialog class="btn-ghost flex-1 py-2.5 rounded-xl">Batal</button>
                <button type="submit" class="btn-gold flex-1 py-2.5 rounded-xl">Simpan</button>
            </div>
        </form>
    </div>
</dialog>

<div class="glass rounded-2xl overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="text-left text-cream/50 text-xs uppercase">
            <tr><th class="p-4">Kategori</th><th>Jumlah Produk</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        @forelse($categories as $c)
            <tr class="border-t border-white/5">
                <td class="p-4">{{ $c->nama }}</td>
                <td>{{ $c->products_count }} produk</td>
                <td>
                    <div class="flex items-center gap-3">
                        <button type="button" data-open-dialog="category-edit-modal-{{ $c->id }}" class="text-gold-300">Edit</button>
                        <form method="POST" action="{{ route('categories.destroy', $c) }}"
                              onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button class="text-red-400">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="p-8 text-center text-cream/50">Belum ada kategori.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

@foreach($categories as $c)
    <dialog id="category-edit-modal-{{ $c->id }}" class="app-modal" data-app-dialog
            data-auto-open="{{ old('_modal_id') === 'category-edit-modal-'.$c->id ? 'true' : 'false' }}">
        <div class="glass rounded-2xl p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-display text-2xl">Edit Kategori</h2>
                <button type="button" data-close-dialog class="app-modal__close btn-ghost" aria-label="Tutup form">×</button>
            </div>

            <form method="POST" action="{{ route('categories.update', $c) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="_modal_id" value="category-edit-modal-{{ $c->id }}">
                <div>
                    <label for="category-name-{{ $c->id }}" class="block text-sm text-cream/60 mb-1">Nama kategori</label>
                    <input type="text" id="category-name-{{ $c->id }}" name="nama" maxlength="50" required
                           value="{{ old('nama', $c->nama) }}"
                           class="input w-full">
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" data-close-dialog class="btn-ghost flex-1 py-2.5 rounded-xl">Batal</button>
                    <button type="submit" class="btn-gold flex-1 py-2.5 rounded-xl">Simpan</button>
                </div>
            </form>
        </div>
    </dialog>
@endforeach
@endsection