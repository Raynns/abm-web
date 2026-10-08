@extends('layouts.admin')
@section('title', $product->exists ? 'Edit Produk' : 'Tambah Produk')
@section('content')
    <div class="max-w-3xl rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8">
        <form method="POST" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @if($product->exists) @method('PUT') @endif
            <div>
                <label for="name" class="mb-2 block text-sm font-semibold">Nama Produk *</label>
                <input id="name" name="name" required maxlength="180" value="{{ old('name', $product->name) }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="Contoh: Fungisida ABC 250 ml">
            </div>
            <div>
                <label for="category_id" class="mb-2 block text-sm font-semibold">Kategori *</label>
                <select id="category_id" name="category_id" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3">
                    <option value="">Pilih kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="price" class="mb-2 block text-sm font-semibold">Harga (Rp) *</label>
                    <input id="price" type="number" name="price" min="0" step="1" required value="{{ old('price', $product->price) }}" class="w-full rounded-xl border border-slate-300 px-4 py-3" placeholder="45000">
                </div>
                <div>
                    <label for="stock" class="mb-2 block text-sm font-semibold">Stok (unit) *</label>
                    <input id="stock" type="number" name="stock" min="0" step="1" required value="{{ old('stock', $product->stock ?? 0) }}" class="w-full rounded-xl border border-slate-300 px-4 py-3" placeholder="20">
                </div>
            </div>
            <div>
                <label for="description" class="mb-2 block text-sm font-semibold">Deskripsi</label>
                <textarea id="description" name="description" rows="4" class="w-full rounded-xl border border-slate-300 px-4 py-3" placeholder="Informasi produk, kemasan, dsb.">{{ old('description', $product->description) }}</textarea>
            </div>
            <div>
                <label for="image" class="mb-2 block text-sm font-semibold">Foto Produk (JPG/PNG/WebP, maksimal 2 MB)</label>
                <input id="image" type="file" name="image" accept="image/*" class="block w-full rounded-xl border border-slate-300 p-3 text-sm">
                @if($product->image_path)
                    <img class="mt-3 h-28 w-28 rounded-xl object-cover" src="{{ asset('storage/'.$product->image_path) }}" alt="Foto {{ $product->name }}">
                @endif
            </div>
            <input type="hidden" name="is_active" value="0">
            <label class="flex items-center gap-3 text-sm font-medium">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->exists ? $product->is_active : true)) class="h-4 w-4 accent-blue-700">Tampilkan produk di toko
            </label>
            <div class="flex flex-wrap gap-3 pt-3">
                <button type="submit" class="rounded-xl bg-blue-800 px-6 py-3 font-semibold text-white hover:bg-blue-900">Simpan Produk</button>
                <a href="{{ route('admin.products.index') }}" class="rounded-xl border border-slate-200 px-6 py-3 font-semibold hover:bg-slate-50">Batal</a>
            </div>
        </form>
    </div>
@endsection
