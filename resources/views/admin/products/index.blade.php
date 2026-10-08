@extends('layouts.admin')
@section('title', 'Kelola Produk')
@section('content')
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-slate-500">Tambah dan perbarui Fungisida atau Insektisida.</p>
        <a href="{{ route('admin.products.create') }}" class="rounded-xl bg-yellow-400 px-5 py-3 text-sm font-semibold text-blue-950 hover:bg-yellow-300">+ Tambah Produk</a>
    </div>
    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full min-w-[780px] text-left text-sm">
            <thead class="bg-slate-50 text-slate-500"><tr><th class="p-4">Produk</th><th class="p-4">Kategori</th><th class="p-4">Harga</th><th class="p-4">Stok</th><th class="p-4">Status</th><th class="p-4">Aksi</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($products as $product)
                    <tr>
                        <td class="p-4 font-semibold">{{ $product->name }}</td>
                        <td class="p-4">{{ $product->category->name }}</td>
                        <td class="p-4">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="p-4">{{ number_format($product->stock, 0, ',', '.') }}</td>
                        <td class="p-4">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <a class="font-semibold text-blue-700 hover:underline" href="{{ route('admin.products.edit', $product) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="font-semibold text-red-700 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-10 text-center text-slate-500">Belum ada produk. Klik Tambah Produk untuk memulai.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $products->links() }}</div>
@endsection
