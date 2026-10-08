@extends('layouts.admin')
@section('title', 'Detail Pesanan')
@section('content')
    <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-blue-700 hover:underline">← Kembali ke transaksi</a>
    @php
        $statusNames = ['pending' => 'Menunggu konfirmasi', 'processing' => 'Diproses', 'completed' => 'Selesai', 'canceled' => 'Dibatalkan'];
    @endphp
    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-2">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
            <h2 class="mb-4 font-bold">Data pembeli</h2>
            <dl class="space-y-3 text-sm">
                <div><dt class="text-slate-500">Nomor pesanan</dt><dd class="break-all font-mono font-medium">{{ $order->order_number }}</dd></div>
                <div><dt class="text-slate-500">Tanggal</dt><dd class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</dd></div>
                <div><dt class="text-slate-500">Nama</dt><dd class="font-medium">{{ $order->customer_name }}</dd></div>
                <div><dt class="text-slate-500">Nomor HP</dt><dd class="font-medium">{{ $order->customer_phone }}</dd></div>
                <div><dt class="text-slate-500">Alamat</dt><dd class="whitespace-pre-line break-words font-medium">{{ $order->shipping_address }}</dd></div>
                <div><dt class="text-slate-500">Pembayaran</dt><dd class="font-medium">{{ strtoupper($order->payment_method) }}</dd></div>
                <div><dt class="text-slate-500">Status</dt><dd class="font-bold text-blue-800">{{ $statusNames[$order->status] ?? $order->status }}</dd></div>
            </dl>
            @if(in_array($order->status, ['pending', 'processing'], true))
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="mt-6 rounded-xl border border-blue-100 bg-blue-50 p-4">
                    @csrf @method('PATCH')
                    <label for="status" class="block text-sm font-semibold">Ubah status pesanan</label>
                    <select name="status" id="status" required class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm">
                        <option value="">Pilih status berikutnya</option>
                        @if($order->status === 'pending')<option value="processing">Diproses</option>@endif
                        @if($order->status === 'processing')<option value="completed">Selesai</option>@endif
                        <option value="canceled">Batalkan pesanan (stok dikembalikan)</option>
                    </select>
                    <button type="submit" class="mt-3 rounded-lg bg-blue-800 px-4 py-2.5 text-sm font-bold text-white hover:bg-blue-900">Simpan status</button>
                    <p class="mt-2 text-xs text-slate-600">Pesanan selesai atau dibatalkan tidak dapat diubah lagi. Periksa pengiriman dan pembayaran sebelum menandai selesai.</p>
                </form>
            @endif
        </section>
        <section class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
            <h2 class="mb-4 font-bold">Barang dipesan</h2>
            <div class="divide-y divide-slate-100 text-sm">
                @forelse($order->items as $item)
                    <div class="flex justify-between gap-3 py-3">
                        <div class="min-w-0"><p class="break-words font-semibold">{{ $item->product_name }}</p><p class="text-slate-500">{{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p></div>
                        <p class="shrink-0 font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                    </div>
                @empty
                    <p class="py-4 text-slate-500">Belum ada item.</p>
                @endforelse
            </div>
            <p class="mt-5 border-t border-slate-200 pt-4 text-right text-lg font-bold">Total produk: Rp {{ number_format($order->total, 0, ',', '.') }}</p>
            <p class="mt-2 text-right text-xs text-slate-500">Ongkos kirim belum termasuk.</p>
        </section>
    </div>
@endsection
