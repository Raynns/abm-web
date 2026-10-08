@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
    <p class="mb-6 text-sm text-slate-600">Selamat datang, <strong>{{ auth()->user()->name }}</strong>. Berikut ringkasan toko.</p>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach([
            ['label' => 'Jumlah produk', 'value' => number_format($productCount, 0, ',', '.')],
            ['label' => 'Produk tersedia', 'value' => number_format($availableCount, 0, ',', '.')],
            ['label' => 'Total pesanan', 'value' => number_format($orderCount, 0, ',', '.')],
            ['label' => 'Pesanan menunggu', 'value' => number_format($pendingCount, 0, ',', '.')],
        ] as $stat)
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">{{ $stat['label'] }}</p>
                <p class="mt-3 text-3xl font-bold text-blue-900">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </div>
    <div class="mt-5 rounded-2xl bg-linear-to-r from-blue-950 via-blue-900 to-blue-700 p-6 text-white">
        <p class="text-sm text-blue-100">Nilai pesanan selesai</p>
        <p class="mt-2 text-3xl font-bold">Rp {{ number_format($completedValue, 0, ',', '.') }}</p>
    </div>
    <div class="mt-8 flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-xl font-bold">Pesanan terbaru</h2>
        <a class="text-sm font-semibold text-blue-700 hover:underline" href="{{ route('admin.orders.index') }}">Semua transaksi →</a>
    </div>
    <div class="mt-4 overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-[600px] w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500"><tr><th class="p-4">No. Pesanan</th><th class="p-4">Pembeli</th><th class="p-4">Total</th><th class="p-4">Status</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($latestOrders as $order)
                    <tr><td class="p-4 font-medium"><a class="text-blue-700 hover:underline" href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a></td><td class="p-4">{{ $order->customer_name }}</td><td class="p-4">Rp {{ number_format($order->total, 0, ',', '.') }}</td><td class="p-4">{{ ucfirst($order->status) }}</td></tr>
                @empty
                    <tr><td colspan="4" class="p-8 text-center text-slate-500">Belum ada pesanan. Fitur checkout customer akan dibuat pada tahap berikutnya.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
