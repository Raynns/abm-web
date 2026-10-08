@extends('layouts.shop')
@section('title', 'Keranjang Belanja')
@section('content')
    <div class="mb-7 flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-blue-700">Belanja produk pertanian</p>
            <h1 class="mt-1 text-3xl font-bold">Keranjang Belanja</h1>
        </div>
        <a class="text-sm font-semibold text-blue-700 hover:underline" href="{{ route('shop.home') }}">← Lanjut belanja</a>
    </div>

    @if(count($lines) === 0)
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
            <p class="text-5xl" aria-hidden="true">🛒</p>
            <h2 class="mt-4 text-xl font-bold">Keranjang Anda masih kosong</h2>
            <p class="mt-2 text-slate-500">Pilih produk terlebih dahulu di halaman toko.</p>
            <a href="{{ route('shop.home') }}" class="mt-5 inline-block rounded-xl bg-yellow-400 px-5 py-3 text-sm font-semibold text-blue-950 hover:bg-yellow-300">Lihat produk</a>
        </div>
    @else
        <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">
            <section class="space-y-3" aria-label="Daftar produk dalam keranjang">
                @foreach($lines as $line)
                    <article class="rounded-2xl border border-stone-200 bg-white p-4 sm:p-5">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                            @if($line['product']?->image_path)
                                <img src="{{ asset('storage/'.$line['product']->image_path) }}" alt="{{ $line['product']->name }}" class="h-28 w-full rounded-xl object-cover sm:w-28" loading="lazy">
                            @else
                                <div class="flex h-28 w-full shrink-0 items-center justify-center rounded-xl bg-blue-50 text-4xl sm:w-28">🌱</div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <h2 class="break-words text-base font-bold sm:text-lg">{{ $line['product']?->name ?? 'Produk telah dihapus' }}</h2>
                                @if($line['product'])
                                    <p class="mt-1 text-sm text-slate-500">Rp {{ number_format($line['product']->price, 0, ',', '.') }} / produk</p>
                                    <p class="mt-1 text-sm font-bold text-blue-800">Subtotal: Rp {{ number_format($line['subtotal'], 0, ',', '.') }}</p>
                                @endif
                                @if($line['issue'])<p class="mt-2 text-sm font-semibold text-red-700">{{ $line['issue'] }}</p>@endif
                            </div>
                            <div class="flex flex-wrap items-end gap-2 sm:justify-end">
                                @if($line['product'] && $line['product']->is_active && $line['product']->stock > 0)
                                    <form class="flex items-end gap-2" method="POST" action="{{ route('cart.update', $line['product_id']) }}">
                                        @csrf @method('PATCH')
                                        <label class="text-xs font-semibold text-slate-600">Jumlah
                                            <input type="number" name="quantity" min="1" max="{{ min(999, $line['product']->stock) }}" value="{{ $line['quantity'] }}" required class="mt-1 block w-20 rounded-lg border border-slate-300 px-3 py-2 text-base">
                                        </label>
                                        <button class="rounded-lg border border-blue-300 px-3 py-2 text-sm font-semibold text-blue-900 hover:bg-blue-50" type="submit">Ubah</button>
                                    </form>
                                @else
                                    <p class="text-sm text-slate-500">Jumlah: {{ $line['quantity'] }}</p>
                                @endif
                                <form method="POST" action="{{ route('cart.destroy', $line['product_id']) }}">
                                    @csrf @method('DELETE')
                                    <button class="rounded-lg border border-red-200 px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-50" type="submit">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>
            <aside class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold">Ringkasan Belanja</h2>
                <div class="mt-5 flex justify-between gap-3 text-sm"><span>Subtotal produk</span><strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong></div>
                <p class="mt-3 text-xs leading-relaxed text-slate-500">Belum termasuk biaya pengiriman bila berlaku. Biaya pengiriman dikonfirmasi terpisah oleh penjual.</p>
                @if($hasIssues)
                    <p class="mt-5 rounded-lg bg-amber-50 p-3 text-sm text-amber-900">Perbarui atau hapus item bermasalah sebelum checkout.</p>
                @else
                    <a href="{{ route('checkout.show') }}" class="mt-5 block rounded-xl bg-yellow-400 px-4 py-3 text-center text-sm font-bold text-blue-950 hover:bg-yellow-300">Lanjut ke Checkout →</a>
                @endif
            </aside>
        </div>
    @endif
@endsection
