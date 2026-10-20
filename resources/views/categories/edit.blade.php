@extends('layouts.app')

@section('content')
<h2>Edit Kategori</h2>

@error('nama') <p style="color:red">{{ $message }}</p> @enderror

<form action="{{ route('categories.update', $category) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="text" nama="nama" value="{{ old('nama', $category->nama) }}" required>
    <button type="submit">Simpan</button>
    <a href="{{ route('categories.index') }}">Batal</a>
</form>
@endsection