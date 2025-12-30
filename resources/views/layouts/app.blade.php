<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('page-styles')
</head>

<body class="font-sans antialiased bg-gray-50">

    {{-- Alpine.js state: sidebarOpen (mobile), sidebarCollapsed (desktop) --}}
    <div x-data="{ sidebarOpen: false, sidebarCollapsed: false }" class="min-h-screen">

        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Overlay untuk mobile --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 lg:hidden" x-cloak>
        </div>

        {{-- Main Content Area - margin kiri mengikuti lebar sidebar --}}
        <div class="min-h-screen flex flex-col transition-all duration-300"
            :class="sidebarCollapsed ? 'lg:ml-[72px]' : 'lg:ml-[240px]'">

            {{-- Header / Navigation --}}
            @include('layouts.navigation')

            {{-- Page Header (Optional) --}}
            @hasSection('header')
                <div class="pt-16 bg-white border-b">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                        @yield('header')
                    </div>
                </div>
            @endif

            {{-- Main Content --}}
            <main class="flex-1 pt-16">
                <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    @yield('content')
                </div>
            </main>

            {{-- Footer - tanpa padding/margin kiri, otomatis menyesuaikan --}}
            @include('layouts.footer')

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @stack('page-scripts')

</body>

</html>
