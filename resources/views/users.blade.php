@extends('layouts.app')
@section('title', 'Pengguna')
@section('content')
@php($user = $user ?? new \App\Models\User())

<div class="flex justify-between items-center mb-5">
    <h1 class="font-display text-3xl">Kelola Pengguna</h1>
    <button type="button" data-open-dialog="user-create-modal" class="btn-gold px-5 py-2.5 rounded-xl flex items-center gap-2">
        <i data-lucide="user-plus" class="w-4 h-4"></i> Tambah Pengguna
    </button>
</div>

<dialog id="user-create-modal" class="app-modal" data-app-dialog data-auto-open="{{ $errors->any() && !old('_modal_id') ? 'true' : 'false' }}">
    <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-display text-2xl">Tambah Pengguna</h2>
            <button type="button" data-close-dialog class="app-modal__close btn-ghost" aria-label="Tutup form">×</button>
        </div>

        <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
            @csrf
            @include('users._fields')

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
            <tr><th class="p-4">Nama</th><th>Email</th><th>Peran</th><th>Dibuat</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        @foreach($users as $u)
            <tr class="border-t border-white/5">
                <td class="p-4 font-medium">
                    {{ $u->name }}
                    @if($u->id === auth()->id())
                        <span class="text-[10px] text-gold-400 ml-1">(Anda)</span>
                    @endif
                </td>
                <td class="text-cream/70">{{ $u->email }}</td>
                <td>
                    <span class="text-xs uppercase px-2 py-1 rounded-full {{ $u->role === 'admin' ? 'bg-gold-500/15 text-gold-300' : 'bg-white/10 text-cream/70' }}">
                        {{ $u->role }}
                    </span>
                </td>
                <td class="text-cream/60">{{ $u->created_at->format('d/m/Y') }}</td>
                <td>
                    <div class="flex items-center gap-3">
                        <button type="button" data-open-dialog="user-edit-modal-{{ $u->id }}" class="text-gold-300">Edit</button>
                        @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('users.destroy', $u) }}"
                                  onsubmit="return confirm('Hapus pengguna ini?')">
                                @csrf @method('DELETE')
                                <button class="text-red-400">Hapus</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@foreach($users as $u)
    <dialog id="user-edit-modal-{{ $u->id }}" class="app-modal" data-app-dialog
            data-auto-open="{{ old('_modal_id') === 'user-edit-modal-'.$u->id ? 'true' : 'false' }}">
        <div class="glass rounded-2xl p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-display text-2xl">Edit Pengguna</h2>
                <button type="button" data-close-dialog class="app-modal__close btn-ghost" aria-label="Tutup form">×</button>
            </div>

            <form method="POST" action="{{ route('users.update', $u) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="_modal_id" value="user-edit-modal-{{ $u->id }}">
                @include('users._fields', ['user' => $u])

                <div class="flex gap-2 pt-2">
                    <button type="button" data-close-dialog class="btn-ghost flex-1 py-2.5 rounded-xl">Batal</button>
                    <button type="submit" class="btn-gold flex-1 py-2.5 rounded-xl">Simpan</button>
                </div>
            </form>
        </div>
    </dialog>
@endforeach
@endsection