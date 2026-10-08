<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin — ABM Web</title>
    <link rel="icon" type="image/png" href="{{ asset('images/abm-mark.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-linear-to-br from-blue-950 via-blue-900 to-blue-700 px-4 py-10">
    <div class="mx-auto flex min-h-[calc(100vh-5rem)] w-full max-w-5xl items-center justify-center">
        <div class="grid w-full overflow-hidden rounded-[2rem] bg-white shadow-2xl lg:grid-cols-[1.05fr_0.95fr]">
            <div class="hidden bg-blue-950 p-10 text-white lg:flex lg:flex-col lg:justify-between">
                <div>
                    <img src="{{ asset('images/abm-logo-clean.png') }}" alt="Logo ABM" class="h-20 w-auto rounded-2xl bg-white p-3">
                    <p class="mt-8 text-sm font-semibold uppercase tracking-[0.25em] text-yellow-300">Administrator</p>
                    <h1 class="mt-4 text-3xl font-bold leading-tight">Sistem Informasi Penjualan Obat Pertanian pada Toko ABM Berbasis WEB</h1>
                    <p class="mt-8 max-w-md text-sm leading-6 text-blue-100">Masuk sebagai admin untuk mengatur katalog, memeriksa pesanan customer, dan memperbarui status transaksi.</p>
                </div>
        
            </div>
            <div class="w-full p-7 sm:p-10">
                <div class="mb-8">
                    <div class="flex items-center gap-3 lg:hidden">
                        <img src="{{ asset('images/abm-logo-clean.png') }}" alt="Logo ABM" class="h-12 w-auto">
                    </div>
                    <p class="mt-4 text-sm font-semibold uppercase tracking-widest text-blue-700">LOGIN</p>
                    <h1 class="mt-2 text-3xl font-bold text-slate-900">Selamat Datang,</h1>
                    <p class="mt-2 text-sm text-slate-500">Masuk untuk mengelola produk dan transaksi.</p>
                </div>
                @if($errors->any())<div class="mb-5 rounded-lg bg-red-50 p-3 text-sm text-red-700" role="alert">{{ $errors->first() }}</div>@endif
                <form action="{{ route('admin.login') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold">Email Admin</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-700 focus:ring-2 focus:ring-blue-100" placeholder="admin@contoh.com">
                    </div>
                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password" class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-700 focus:ring-2 focus:ring-blue-100">
                    </div>
                    <button class="w-full rounded-xl bg-blue-800 px-4 py-3 font-semibold text-white hover:bg-blue-900" type="submit">Masuk ke Dashboard</button>
                </form>
                <a href="{{ route('shop.home') }}" class="mt-6 block text-center text-sm font-medium text-blue-700 hover:underline">← Kembali ke toko</a>
            </div>
        </div>
    </div>
</body>
</html>
