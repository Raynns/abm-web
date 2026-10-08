<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Toko Pertanian') — ABM Web</title>
    <link rel="icon" type="image/png" href="{{ asset('images/abm-mark.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <header class="sticky top-0 z-30 border-b border-blue-100 bg-white/95 backdrop-blur">
        <div class="mx-auto flex flex-wrap items-center justify-between gap-3 px-4 py-4 sm:px-8">
            <a href="{{ route('shop.home') }}" class="flex items-center gap-3" aria-label="Beranda ABM Web">
                <img src="{{ asset('images/abm-logo-clean.png') }}" alt="Logo ABM Artha Buana Mandiri" class="h-12 w-auto sm:h-14">
            </a>
            <nav class="flex flex-wrap items-center gap-2 sm:gap-3" aria-label="Navigasi utama">
                <a href="{{ route('shop.home') }}" class="rounded-xl px-3 py-2 text-sm font-semibold text-blue-950 hover:bg-blue-50">Produk</a>
                <a href="{{ route('cart.index') }}" class="rounded-xl bg-yellow-400 px-3 py-2 text-sm font-bold text-blue-950 hover:bg-yellow-300">Keranjang ({{ app(\App\Services\CartService::class)->count(request()) }})</a>
            </nav>
        </div>
    </header>
    <main class="mx-auto max-w-7xl px-4 py-7 sm:px-8 sm:py-10">
        @if(session('success'))
            <div role="status" class="mb-5 rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div role="alert" class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                <strong>Periksa kembali:</strong>
                <ul class="mt-1 list-inside list-disc space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        @yield('content')
    </main>
    <footer class="border-t border-slate-200 bg-white p-6 text-center text-sm text-slate-500">ABM Web — Artha Buana Mandiri</footer>
</body>
</html>
