@extends('layouts.main')
@section('content')
<section class="max-w-3xl mx-auto px-5 pt-32 pb-20">
    <h1 class="font-display text-3xl mb-6">Edit <span class="gold-text">Produk</span></h1>
    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="glass rounded-3xl p-6 md:p-8">
        @method('PUT')
        @include('products._form')
    </form>
</section>
@endsection