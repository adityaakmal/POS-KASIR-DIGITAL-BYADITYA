@extends('layouts.app')
@section('title', 'Form Produk')
@section('content')

<h1 class="font-display text-3xl mb-5">{{ $product->exists ? 'Edit' : 'Tambah' }} Produk</h1>

<form method="POST" enctype="multipart/form-data"
      action="{{ $product->exists ? route('products.update', $product) : route('products.store') }}"
      data-product-form
      class="glass rounded-2xl p-6 max-w-xl space-y-4">
    @csrf
    @if($product->exists) @method('PUT') @endif

    @include('products._fields')

    <div class="flex gap-3">
        <button class="btn-gold px-6 py-2.5 rounded-xl">Simpan</button>
        <a href="{{ route('products.index') }}" class="btn-ghost px-6 py-2.5 rounded-xl">Batal</a>
    </div>
</form>
@endsection