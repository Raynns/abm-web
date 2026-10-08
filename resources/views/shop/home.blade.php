@extends('layouts.shop')
@section('title', 'Katalog Produk Pertanian')
@section('content')
    <section class="rounded-3xl bg-linear-to-r from-blue-950 via-blue-900 to-blue-700 px-6 py-10 text-white sm:px-12 sm:py-14">
        <p class="text-sm font-semibold uppercase tracking-widest text-yellow-300">Toko pertanian</p>
        <h1 class="mt-3 max-w-2xl text-3xl font-bold leading-tight sm:text-5xl">Kebutuhan pertanian, lebih mudah ditemukan.</h1>
        <p class="mt-4 max-w-xl text-sm leading-6 text-blue-100 sm:text-base">Pilih Fungisida atau Insektisida, masukkan ke keranjang, lalu checkout tanpa perlu akun.</p>
    </section>
    <div class="mt-8 flex flex-wrap gap-2">
        <a href="{{ route('shop.home') }}" class="rounded-full px-5 py-2 text-sm font-semibold {{ !$category ? 'bg-yellow-400 text-blue-950' : 'bg-white text-blue-900 ring-1 ring-blue-200 hover:bg-blue-50' }}">Semua Produk</a>
        @foreach($categories as $item)
            <a href="{{ route('shop.home', ['kategori' => $item->slug]) }}" class="rounded-full px-5 py-2 text-sm font-semibold {{ $category === $item->slug ? 'bg-yellow-400 text-blue-950' : 'bg-white text-blue-900 ring-1 ring-blue-200 hover:bg-blue-50' }}">{{ $item->name }}</a>
        @endforeach
    </div>
    <div class="mt-7 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse($products as $product)
            <article class="flex flex-col overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-sm">
                @if($product->image_path)
                    <img src="{{ asset('storage/'.$product->image_path) }}" alt="{{ $product->name }}" class="h-48 w-full object-cover" loading="lazy">
                @else
                    <div class="flex h-48 items-center justify-center bg-blue-50 text-4xl" aria-label="Gambar produk belum ada">🌱</div>
                @endif
                <div class="flex flex-1 flex-col p-5">
                    <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">{{ $product->category->name }}</p>
                    <h2 class="mt-2 min-h-12 text-lg font-bold">{{ $product->name }}</h2>
                    <p class="mt-2 text-xl font-extrabold text-blue-800">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <p class="mt-2 text-sm text-slate-500">{{ $product->stock > 0 ? 'Stok: '.$product->stock : 'Stok habis' }}</p>
                    <p class="mt-3 mb-4 line-clamp-3 text-sm text-slate-600">{{ $product->description ?: 'Informasi produk tersedia.' }}</p>
                    @if($product->stock > 0)
                        <form action="{{ route('cart.store', $product) }}" method="POST" class="mt-auto flex items-end gap-2">
                            @csrf
                            <label class="min-w-0 flex-1 text-xs font-medium text-slate-600">Jumlah
                                <input type="number" name="quantity" value="1" min="1" max="{{ min(999, $product->stock) }}" required inputmode="numeric" class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-base text-slate-900 focus:border-blue-700 focus:outline-none">
                            </label>
                            <button type="submit" class="rounded-lg bg-yellow-400 px-4 py-2.5 text-sm font-semibold text-blue-950 hover:bg-yellow-300">+ Keranjang</button>
                        </form>
                    @else
                        <p class="mt-auto rounded-lg bg-slate-100 px-3 py-2 text-center text-sm font-medium text-slate-500">Belum tersedia</p>
                    @endif
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-slate-500">Belum ada produk tersedia pada kategori ini.
Silakan pilih kategori lainnya untuk melihat produk yang tersedia.</div>
        @endforelse
    </div>
    <div class="mt-8">{{ $products->links() }}</div>
@endsection
