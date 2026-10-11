<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <link rel="icon" type="image/png" href="{{ asset('kpu-logo.png') }}?v=20261005">

    <meta
        name="description"
        content="Sistem Informasi Buku Tamu dan Absensi Virtual KPU Kota Ternate"
    >

    <title>
        @yield('title', 'KPU Kota Ternate')
    </title>

    @if (file_exists(public_path('hot')) || ! file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        @php
            $buildManifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
            $buildCss = $buildManifest['resources/css/app.css']['file'] ?? null;
            $buildJs = $buildManifest['resources/js/app.js']['file'] ?? null;
        @endphp

        @if ($buildCss)
            <link rel="stylesheet" href="{{ asset('build/'.$buildCss) }}">
        @endif

        @if ($buildJs)
            <script type="module" src="{{ asset('build/'.$buildJs) }}"></script>
        @endif
    @endif
</head>

<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">

<div class="min-h-screen">

    {{-- =========================
         MOBILE OVERLAY
    ========================== --}}
    <div
        id="sidebar-overlay"
        class="fixed inset-0 z-40 hidden bg-slate-950/50 backdrop-blur-sm lg:hidden"
    ></div>


    {{-- =========================
         SIDEBAR
    ========================== --}}
    <aside
        id="sidebar"
        class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col border-r border-slate-200 bg-white transition-transform duration-300 lg:translate-x-0"
    >

        {{-- Logo --}}
        <div class="flex h-20 items-center border-b border-slate-100 px-6">

            <div class="flex items-center gap-3">

                <div
                    class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-900 text-sm font-black tracking-tight text-white shadow-lg shadow-slate-900/20"
                >
                    KPU
                </div>

                <div>
                    <h1 class="text-sm font-extrabold tracking-wide text-slate-900">
                        KPU KOTA TERNATE
                    </h1>

                    <p class="mt-0.5 text-xs text-slate-400">
                        Sistem Informasi
                    </p>
                </div>

            </div>

            {{-- Close mobile --}}
            <button
                id="close-sidebar"
                type="button"
                class="ml-auto rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700 lg:hidden"
            >
                ✕
            </button>

        </div>


        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-4 py-6">

            <p class="mb-3 px-3 text-[11px] font-bold uppercase tracking-[0.15em] text-slate-400">
                Menu Utama
            </p>


            {{-- Dashboard --}}
            <a
                href="/"
                class="group mb-1 flex items-center gap-3 rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-sm"
            >
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l9-9 9 9M5 10v10h14V10"/>
                    </svg>
                </span>

                Dashboard
            </a>


          {{-- Data Tamu --}}
            <a
                href="{{ route('admin.tamu.index') }}"
                class="group mb-1 flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
            >
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-8a4 4 0 11-8 0 4 4 0 018 0zm6 1a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </span>

                <span class="flex-1">
                    Data Tamu
                </span>

               <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-500">
                    {{ $totalTamu }}
                </span>
            </a>


            {{-- Absensi --}}
            <a
                href="#"
                class="group mb-1 flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
            >
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>

                Absensi
            </a>


            {{-- Pengguna --}}
            <a
                href="#"
                class="group mb-1 flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
            >
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-50 text-violet-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m8-10a4 4 0 100-8 4 4 0 000 8zm10 10v-2a4 4 0 00-3-3.87m-1-8a4 4 0 100-8"/>
                    </svg>
                </span>

                Pengguna
            </a>


            {{-- Laporan --}}
            <a
                href="#"
                class="group mb-1 flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
            >
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 17v-6m3 6V7m3 10v-4M4 19h16"/>
                    </svg>
                </span>

                Laporan
            </a>


            <div class="my-6 border-t border-slate-100"></div>


            <p class="mb-3 px-3 text-[11px] font-bold uppercase tracking-[0.15em] text-slate-400">
                Sistem
            </p>


            {{-- Pengaturan --}}
            <a
                href="#"
                class="mb-1 flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
            >
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                    ⚙
                </span>

                Pengaturan
            </a>

        </nav>


        {{-- Sidebar Footer --}}
        <div class="border-t border-slate-100 p-4">

            <div class="rounded-2xl bg-slate-50 p-4">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-xs font-bold text-white">
                        A
                    </div>

                    <div class="min-w-0 flex-1">

                        <p class="truncate text-sm font-bold text-slate-800">
                            Administrator
                        </p>

                        <p class="truncate text-xs text-slate-400">
                            Admin Sistem
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </aside>


    {{-- =========================
         MAIN AREA
    ========================== --}}
    <div class="lg:pl-72">

        {{-- TOPBAR --}}
        <header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/90 backdrop-blur-xl">

            <div class="flex h-20 items-center justify-between px-4 sm:px-6 lg:px-8">

                {{-- Mobile Menu --}}
                <button
                    id="open-sidebar"
                    type="button"
                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm hover:bg-slate-50 lg:hidden"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>


                {{-- Breadcrumb --}}
                <div class="hidden sm:block">

                    <p class="text-xs font-medium text-slate-400">
                        KPU Kota Ternate
                    </p>

                    <p class="text-sm font-bold text-slate-800">
                        @yield('page-title', 'Dashboard')
                    </p>

                </div>


                {{-- Right --}}
                <div class="ml-auto flex items-center gap-3">

                    {{-- Notification --}}
                    <button
                        type="button"
                        class="relative flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-slate-900"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.5-1.5A2 2 0 0118 14V10a6 6 0 10-12 0v4a2 2 0 01-.5 1.5L4 17h5m6 0a3 3 0 01-6 0"/>
                        </svg>

                        <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                    </button>


                    {{-- Profile --}}
                    <div class="flex items-center gap-3">

                        <div class="hidden text-right md:block">

                            <p class="text-sm font-bold text-slate-800">
                                Administrator
                            </p>

                            <p class="text-xs text-slate-400">
                                Admin Sistem
                            </p>

                        </div>

                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-sm font-bold text-white shadow-md">
                            A
                        </div>

                    </div>

                </div>

            </div>

        </header>


        {{-- CONTENT --}}
        <main class="min-h-[calc(100vh-5rem)] p-4 sm:p-6 lg:p-8">

            @yield('content')

        </main>

    </div>

</div>


{{-- Mobile Sidebar Script --}}
<script>

    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const openSidebar = document.getElementById('open-sidebar');
    const closeSidebar = document.getElementById('close-sidebar');

    function openMenu() {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeMenu() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    if (openSidebar) {
        openSidebar.addEventListener('click', openMenu);
    }

    if (closeSidebar) {
        closeSidebar.addEventListener('click', closeMenu);
    }

    if (overlay) {
        overlay.addEventListener('click', closeMenu);
    }

</script>

</body>
</html>