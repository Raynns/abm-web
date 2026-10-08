@extends('layouts.admin')
@section('title', 'Transaksi')
@section('content')
    <div class="mb-5 rounded-xl bg-blue-50 p-4 text-sm text-blue-900">Pesanan Customer akan masuk otomatis setelah checkout. Klik <strong>Detail</strong> untuk melihat alamat, jumlah produk, dan mengubah status pesanan.</div>
    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-[760px] w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500"><tr><th class="p-4">No. Pesanan</th><th class="p-4">Tanggal</th><th class="p-4">Pembeli</th><th class="p-4">Total</th><th class="p-4">Status</th><th class="p-4">Aksi</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($orders as $order)
                    @php $statusNames = ['pending' => 'Menunggu', 'processing' => 'Diproses', 'completed' => 'Selesai', 'canceled' => 'Dibatalkan']; @endphp
                    <tr>
                        <td class="p-4 font-mono text-xs font-medium">{{ $order->order_number }}</td>
                        <td class="p-4">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-4">{{ $order->customer_name }}</td>
                        <td class="p-4 whitespace-nowrap font-semibold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="p-4">{{ $statusNames[$order->status] ?? $order->status }}</td>
                        <td class="p-4"><a href="{{ route('admin.orders.show', $order) }}" class="font-semibold text-blue-700 hover:underline">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-10 text-center text-slate-500">Belum ada pesanan. Pesanan akan tampil setelah customer menyelesaikan checkout.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $orders->links() }}</div>
@endsection
