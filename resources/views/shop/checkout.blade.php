@extends('layouts.shop')
@section('title', 'Checkout')
@section('content')
    <a href="{{ route('cart.index') }}" class="text-sm font-semibold text-blue-700 hover:underline">← Kembali ke keranjang</a>
    <h1 class="mt-4 text-3xl font-bold">Checkout tanpa Login</h1>
    <p class="mt-2 text-sm text-slate-600">Isi identitas dan alamat pengiriman untuk mencatat pesanan Anda.</p>

    <form method="POST" action="{{ route('checkout.store') }}" class="mt-7 grid grid-cols-1 items-start gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
        @csrf
        <input type="hidden" name="expected_total" value="{{ $subtotal }}">
        <section class="rounded-2xl border border-stone-200 bg-white p-5 sm:p-7">
            <h2 class="text-xl font-bold">Identitas Pembeli</h2>
            <div class="mt-5 space-y-5">
                <div>
                    <label for="customer_name" class="mb-2 block text-sm font-semibold">Nama lengkap <span class="text-red-600">*</span></label>
                    <input id="customer_name" name="customer_name" type="text" required minlength="2" maxlength="150" autocomplete="name" value="{{ old('customer_name') }}" placeholder="Contoh: Budi Santoso" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-base focus:border-blue-700 focus:outline-none @error('customer_name') border-red-500 @enderror">
                    @error('customer_name')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="customer_phone" class="mb-2 block text-sm font-semibold">Nomor HP / WhatsApp <span class="text-red-600">*</span></label>
                    <input id="customer_phone" name="customer_phone" type="tel" required maxlength="25" autocomplete="tel" value="{{ old('customer_phone') }}" placeholder="Contoh: 081234567890" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-base focus:border-blue-700 focus:outline-none @error('customer_phone') border-red-500 @enderror">
                    @error('customer_phone')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="shipping_address" class="mb-2 block text-sm font-semibold">Alamat pengiriman lengkap <span class="text-red-600">*</span></label>
                    <textarea id="shipping_address" name="shipping_address" required minlength="10" maxlength="2000" rows="5" autocomplete="street-address" placeholder="Jalan, nomor rumah, RT/RW, desa, kecamatan, kabupaten, provinsi, kode pos" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-base focus:border-blue-700 focus:outline-none @error('shipping_address') border-red-500 @enderror">{{ old('shipping_address') }}</textarea>
                    @error('shipping_address')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="mt-6 rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-900">
                <p class="font-bold">Metode pembayaran: COD (bayar saat diterima)</p>
                <p class="mt-1">Belum tersedia pembayaran online. Ketersediaan layanan kirim dan ongkos kirim harus dikonfirmasi dengan penjual.</p>
            </div>
        </section>
        <aside class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm sm:p-6">
            <h2 class="text-lg font-bold">Ringkasan Pesanan</h2>
            <div class="mt-4 divide-y divide-stone-100">
                @foreach($lines as $line)
                    <div class="flex justify-between gap-3 py-3 text-sm">
                        <div><p class="font-semibold">{{ $line['product']->name }}</p><p class="mt-1 text-slate-500">{{ $line['quantity'] }} × Rp {{ number_format($line['product']->price, 0, ',', '.') }}</p></div>
                        <span class="shrink-0 font-semibold">Rp {{ number_format($line['subtotal'], 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 flex items-center justify-between gap-3 border-t pt-4"><span class="font-semibold">Total produk</span><strong class="text-xl text-blue-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</strong></div>
            <p class="mt-3 text-xs text-slate-500">Total belum mencakup ongkos kirim. Harga dan stok diverifikasi ulang saat pesanan dikirim.</p>
            <button type="submit" class="mt-6 w-full rounded-xl bg-yellow-400 px-5 py-3.5 text-base font-bold text-blue-950 hover:bg-yellow-300">Buat Pesanan</button>
            <p class="mt-2 text-center text-xs text-slate-500">Pesanan dicatat dengan status Menunggu Konfirmasi.</p>
        </aside>
    </form>
@endsection
