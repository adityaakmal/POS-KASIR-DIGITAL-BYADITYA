@extends('layouts.app')
@section('title', 'Edit Outlet')
@section('content')

<h1 class="font-display text-3xl mb-5">Edit Outlet</h1>

<div class="glass rounded-2xl p-6 max-w-xl">
    @include('outlet._form')
</div>
@endsection