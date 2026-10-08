@extends('layouts.admin')
@section('title','Form Kategori')
@section('content')
<div class="max-w-xl rounded-2xl bg-white p-6 border">
<form method="POST" action="{{$category->exists?route('admin.categories.update',$category):route('admin.categories.store')}}">
@csrf @if($category->exists) @method('PUT') @endif
<label class="block mb-2 font-semibold">Nama Kategori</label>
<input name="name" value="{{old('name',$category->name)}}" class="w-full rounded-xl border p-3 mb-4">
<button class="rounded-xl bg-blue-800 px-5 py-3 text-white">Simpan</button>
</form></div>
@endsection