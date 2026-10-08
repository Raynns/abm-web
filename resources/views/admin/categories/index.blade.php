@extends('layouts.admin')
@section('title','Kategori Produk')

@section('content')
<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <div>
        <p class="text-sm text-slate-500">Kelola kategori produk pertanian.</p>
    </div>

    <a href="{{ route('admin.categories.create') }}"
       class="rounded-xl bg-yellow-400 px-5 py-3 text-sm font-semibold text-blue-950 hover:bg-yellow-300">
        + Tambah Kategori
    </a>
</div>

<div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
    <table class="w-full min-w-[700px] text-left text-sm">
        <thead class="bg-slate-50 text-slate-500">
            <tr>
                <th class="p-4">Kategori</th>
                <th class="p-4">Slug</th>
                <th class="p-4">Jumlah Produk</th>
                <th class="p-4">Aksi</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-slate-100">
            @forelse($categories as $category)
                <tr>
                    <td class="p-4">
                        <div class="font-semibold text-slate-800">
                            {{ $category->name }}
                        </div>
                    </td>

                    <td class="p-4 text-slate-600">
                        {{ $category->slug }}
                    </td>

                    <td class="p-4">
                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                            {{ $category->products_count }} Produk
                        </span>
                    </td>

                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.categories.edit',$category) }}"
                               class="font-semibold text-blue-700 hover:underline">
                                Edit
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.categories.destroy',$category) }}"
                                  onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="font-semibold text-red-700 hover:underline">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-10 text-center text-slate-500">
                        Belum ada kategori.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
