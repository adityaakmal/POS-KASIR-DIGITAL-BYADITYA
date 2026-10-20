@extends('layouts.app')
@section('title', 'Form Pengguna')
@section('content')

<h1 class="font-display text-3xl mb-5">{{ $user->exists ? 'Edit' : 'Tambah' }} Pengguna</h1>

<form method="POST"
      action="{{ $user->exists ? route('users.update', $user) : route('users.store') }}"
      class="glass rounded-2xl p-6 max-w-xl space-y-4">
    @csrf
    @if($user->exists) @method('PUT') @endif

    @include('users._fields')

    <div class="flex gap-3">
        <button class="btn-gold px-6 py-2.5 rounded-xl">Simpan</button>
        <a href="{{ route('users.index') }}" class="btn-ghost px-6 py-2.5 rounded-xl">Batal</a>
    </div>
</form>
@endsection