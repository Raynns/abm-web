<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — ABM Web</title>
    <link rel="icon" type="image/png" href="{{ asset('images/abm-mark.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    <div class="min-h-screen lg:flex">
        <aside class="hidden w-72 shrink-0 bg-blue-950 px-5 py-8 text-white lg:block">
            <div class="rounded-2xl bg-white px-4 py-4 shadow-sm">
                <img src="{{ asset('images/abm-logo-clean.png') }}" alt="Logo ABM" class="h-14 w-auto">
                <p class="mt-2 text-xs font-medium text-slate-500">Panel administrasi</p>
            </div>
            <nav class="mt-8 space-y-2 text-sm font-medium" aria-label="Menu admin">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-yellow-400 font-bold text-blue-950 shadow-sm' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-yellow-400 font-bold text-blue-950 shadow-sm' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 3 9 5-9 5-9-5 9-5Z"/><path d="M3 8v9l9 5 9-5V8"/><path d="M12 13v9"/></svg>
                    <span>Kelola Produk</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-yellow-400 font-bold text-blue-950 shadow-sm' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 3h16v18l-4-2-4 2-4-2-4 2V3Z"/><path d="M8 8h8M8 12h8M8 16h5"/></svg>
                    <span>Transaksi</span>
                </a>
                <a href="{{ route('shop.home') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 transition-colors text-white/90 hover:bg-white/10 hover:text-white">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 10h16l-1-6H5l-1 6Z"/><path d="M4 10v10h16V10"/><path d="M9 20v-6h6v6"/></svg>
                    <span>Lihat Toko</span>
                </a>
            </nav>
        </aside>
        <div class="min-w-0 flex-1">
            <header class="border-b border-slate-200 bg-white px-4 py-4 sm:px-8">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="rounded-xl border border-blue-100 bg-blue-50 p-2 lg:hidden">
                            <img src="{{ asset('images/abm-mark.png') }}" alt="ABM" class="h-8 w-8">
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">ABM Web</p>
                            <h1 class="text-xl font-bold sm:text-2xl">@yield('title', 'Dashboard')</h1>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-blue-200 px-4 py-2 text-sm font-semibold text-blue-900 hover:bg-blue-50"><svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m10 17 5-5-5-5"/><path d="M15 12H3"/><path d="M12 3h6a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3h-6"/></svg><span>Logout</span></button>
                    </form>
                </div>
                <nav class="mt-4 flex gap-2 overflow-x-auto pb-1 text-sm lg:hidden" aria-label="Menu admin seluler">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex shrink-0 items-center gap-2 whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-yellow-400 text-blue-950' : 'bg-blue-50 text-blue-800 hover:bg-blue-100' }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="inline-flex shrink-0 items-center gap-2 whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-yellow-400 text-blue-950' : 'bg-blue-50 text-blue-800 hover:bg-blue-100' }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 3 9 5-9 5-9-5 9-5Z"/><path d="M3 8v9l9 5 9-5V8"/><path d="M12 13v9"/></svg>
                        <span>Produk</span>
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="inline-flex shrink-0 items-center gap-2 whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-yellow-400 text-blue-950' : 'bg-blue-50 text-blue-800 hover:bg-blue-100' }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 3h16v18l-4-2-4 2-4-2-4 2V3Z"/><path d="M8 8h8M8 12h8M8 16h5"/></svg>
                        <span>Transaksi</span>
                    </a>
                    <a href="{{ route('shop.home') }}" class="inline-flex shrink-0 items-center gap-2 whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium transition-colors bg-blue-50 text-blue-800 hover:bg-blue-100">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 10h16l-1-6H5l-1 6Z"/><path d="M4 10v10h16V10"/><path d="M9 20v-6h6v6"/></svg>
                        <span>Toko</span>
                    </a>
                </nav>
            </header>
            <main class="mx-auto max-w-7xl px-4 py-7 sm:px-8">
                @if(session('success'))
                    <div class="mb-5 rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800" role="status">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
                        <strong>Periksa input Anda:</strong>
                        <ul class="mt-1 list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
