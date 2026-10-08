@extends('layouts.shop')
@section('title', 'Pesanan Berhasil')
@section('content')
    <div class="mx-auto max-w-3xl rounded-3xl border border-blue-200 bg-white p-6 text-center shadow-sm sm:p-10">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-yellow-100 text-3xl text-yellow-600">✓</div>
        <h1 class="mt-5 text-3xl font-bold text-blue-900">Pesanan berhasil dibuat</h1>
        <p class="mt-3 text-sm text-slate-600">Pesanan Anda tercatat dan menunggu konfirmasi penjual. Simpan nomor pesanan berikut.</p>
        <p class="mt-5 break-all rounded-xl bg-blue-50 p-4 font-mono text-lg font-bold text-blue-900">{{ $order->order_number }}</p>
        <div class="mt-6 space-y-3 text-left">
            @foreach($order->items as $item)
                <div class="flex justify-between gap-3 border-b border-stone-100 pb-3 text-sm"><div><strong>{{ $item->product_name }}</strong><p class="text-slate-500">{{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p></div><span class="font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span></div>
            @endforeach
            <div class="flex justify-between gap-3 pt-3 text-lg"><span class="font-bold">Total produk</span><strong class="text-blue-900">Rp {{ number_format($order->total, 0, ',', '.') }}</strong></div>
        </div>
        <p class="mt-5 text-left text-sm text-slate-600">Pembayaran: <strong>COD</strong>. Biaya kirim, bila ada, dikonfirmasi secara terpisah oleh penjual. Halaman ini hanya bisa dibuka dari sesi browser yang melakukan pemesanan.</p>
        <a href="{{ route('shop.home') }}" class="mt-7 inline-block rounded-xl bg-yellow-400 px-6 py-3 font-bold text-blue-950 hover:bg-yellow-300">Kembali ke Toko</a>
    </div>
@endsection
