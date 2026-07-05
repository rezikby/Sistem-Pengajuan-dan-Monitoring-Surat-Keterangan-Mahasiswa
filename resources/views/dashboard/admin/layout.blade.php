<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Admin') - Sistem Manajemen</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body class="h-full bg-slate-100">

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        <x-admin.sidebar />

        {{-- Main content --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Topbar (opsional) --}}
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 shrink-0">
                <h1 class="text-lg font-semibold text-slate-800">@yield('title', 'Dashboard')</h1>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-slate-600">{{ session('role') }}</span>
                </div>
            </header>

            {{-- Konten halaman --}}
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>