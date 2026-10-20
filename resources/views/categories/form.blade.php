@extends('layouts.app')
@section('title', $category->exists ? 'Edit Kategori' : 'Tambah Kategori')
@section('content')

<div class="max-w-lg">
    <h1 class="font-display text-3xl mb-5">
        {{ $category->exists ? 'Edit Kategori' : 'Tambah Kategori' }}
    </h1>

    <form method="POST"
          action="{{ $category->exists ? route('categories.update', $category) : route('categories.store') }}"
          class="glass rounded-2xl p-6 space-y-4">
        @csrf
        @if($category->exists)
            @method('PUT')
        @endif

        <div>
            <label for="nama" class="block text-sm text-cream/60 mb-1">Nama kategori</label>
            <input type="text" id="nama" name="nama" maxlength="50" required autofocus
                   value="{{ old('nama', $category->nama) }}"
                   placeholder="Contoh: Martabak Manis"
                   class="input w-full">
        </div>

        <div class="flex gap-2 pt-2">
            <a href="{{ route('categories.index') }}" class="btn-ghost flex-1 py-2.5 rounded-xl text-center">Batal</a>
            <button type="submit" class="btn-gold flex-1 py-2.5 rounded-xl">Simpan</button>
        </div>
    </form>
</div>
@endsection