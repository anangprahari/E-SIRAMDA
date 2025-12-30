{{-- Sidebar dengan fitur collapse/expand --}}
<aside x-data="{
    asetOpen: {{ request()->is('asets*', 'aset-lancars*') ? 'true' : 'false' }},
    mutasiOpen: {{ request()->is('mutasi*') ? 'true' : 'false' }}
}"
    class="fixed inset-y-0 left-0 bg-white border-r border-gray-200 shadow-lg z-40
           transform transition-all duration-300 ease-in-out
           -translate-x-full lg:translate-x-0"
    :class="{
        'translate-x-0': sidebarOpen,
        'w-[240px]': !sidebarCollapsed,
        'w-[72px]': sidebarCollapsed
    }">

    {{-- Header: Logo + Toggle Button --}}
    <div
        class="h-16 flex items-center justify-between px-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">

        {{-- Logo (disembunyikan saat collapsed) --}}
        <a href="{{ url('/') }}" class="flex items-center gap-2 transition-opacity duration-300"
            :class="sidebarCollapsed ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100'">
            <img src="{{ asset('assets/img/backgrounds/LogoSiramda.png') }}" alt="Logo" class="h-15 w-auto">
        </a>

        {{-- Toggle Button (desktop only) --}}
        <button @click="sidebarCollapsed = !sidebarCollapsed"
            class="hidden lg:flex p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200"
            :class="sidebarCollapsed ? 'mx-auto' : ''">
            <svg class="w-5 h-5 transition-transform duration-300" :class="sidebarCollapsed ? 'rotate-180' : ''"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
        </button>

        {{-- Close Button (mobile only) --}}
        <button @click="sidebarOpen = false"
            class="lg:hidden p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Navigation Menu --}}
    <nav class="flex-1 px-3 py-6 space-y-2 overflow-y-auto h-[calc(100vh-4rem)]">

        {{-- Beranda --}}
        <a href="{{ route('dashboard') }}"
            class="group relative flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200
                  {{ request()->is('dashboard*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}"
            x-tooltip="sidebarCollapsed ? 'Beranda' : ''">

            <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-200 {{ request()->is('dashboard*') ? '' : 'group-hover:scale-110' }}"
                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>

            <span class="font-medium transition-all duration-300 whitespace-nowrap overflow-hidden"
                :class="sidebarCollapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                Beranda
            </span>

            @if (request()->is('dashboard*'))
                <span class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-blue-700 rounded-l"></span>
            @endif
        </a>

        {{-- ASET (dengan dropdown) --}}
        <div>
            <button @click="asetOpen = !asetOpen"
                class="group w-full relative flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200
                           {{ request()->is('asets*', 'aset-lancars*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">

                <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-200 {{ request()->is('asets*', 'aset-lancars*') ? '' : 'group-hover:scale-110' }}"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>

                <span class="flex-1 text-left font-medium transition-all duration-300 whitespace-nowrap overflow-hidden"
                    :class="sidebarCollapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                    Aset
                </span>

                <svg class="w-4 h-4 flex-shrink-0 transition-all duration-300"
                    :class="[
                        asetOpen ? 'rotate-180' : '',
                        sidebarCollapsed ? 'w-0 opacity-0' : 'w-4 opacity-100'
                    ]"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>

                @if (request()->is('asets*', 'aset-lancars*'))
                    <span class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-blue-700 rounded-l"></span>
                @endif
            </button>

            {{-- Dropdown Menu (disembunyikan saat collapsed) --}}
            <div x-show="asetOpen && !sidebarCollapsed" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1"
                class="mt-2 ml-4 space-y-1 pl-4 border-l-2 border-blue-200" x-cloak>

                <a href="{{ route('asets.index') }}"
                    class="block px-4 py-2.5 rounded-lg text-sm transition-all duration-200
                          {{ request()->is('asets*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Aset Tetap
                    </div>
                </a>

                <a href="{{ route('aset-lancars.index') }}"
                    class="block px-4 py-2.5 rounded-lg text-sm transition-all duration-200
                          {{ request()->is('aset-lancars*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Aset Lancar
                    </div>
                </a>
            </div>
        </div>

        {{-- MUTASI ASET (dengan dropdown) --}}
        <div>
            <button @click="mutasiOpen = !mutasiOpen"
                class="group w-full relative flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200
                           {{ request()->is('mutasi*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">

                <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-200 {{ request()->is('mutasi*') ? '' : 'group-hover:scale-110' }}"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>

                <span class="flex-1 text-left font-medium transition-all duration-300 whitespace-nowrap overflow-hidden"
                    :class="sidebarCollapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                    Mutasi Aset
                </span>

                <svg class="w-4 h-4 flex-shrink-0 transition-all duration-300"
                    :class="[
                        mutasiOpen ? 'rotate-180' : '',
                        sidebarCollapsed ? 'w-0 opacity-0' : 'w-4 opacity-100'
                    ]"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>

                @if (request()->is('mutasi*'))
                    <span class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-blue-700 rounded-l"></span>
                @endif
            </button>

            {{-- Dropdown Menu --}}
            <div x-show="mutasiOpen && !sidebarCollapsed" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1"
                class="mt-2 ml-4 space-y-1 pl-4 border-l-2 border-blue-200" x-cloak>

                <a href="{{ route('mutasi.index') }}"
                    class="block px-4 py-2.5 rounded-lg text-sm transition-all duration-200
                          {{ request()->is('mutasi') && !request()->is('mutasi/riwayat*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Daftar Mutasi
                    </div>
                </a>

                <a href="{{ route('mutasi.riwayat') }}"
                    class="block px-4 py-2.5 rounded-lg text-sm transition-all duration-200
                          {{ request()->is('mutasi/riwayat*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Riwayat Mutasi
                    </div>
                </a>
            </div>
        </div>

        {{-- MANAJEMEN PENGGUNA --}}
        <a href="{{ route('users.index') }}"
            class="group relative flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200
                  {{ request()->is('users*') ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-700' }}">

            <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-200 {{ request()->is('users*') ? '' : 'group-hover:scale-110' }}"
                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6H2v-2a4 4 0 014-4h7m0 0a4 4 0 100-8 4 4 0 000 8zm6-4a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>

            <span class="font-medium transition-all duration-300 whitespace-nowrap overflow-hidden"
                :class="sidebarCollapsed ? 'w-0 opacity-0' : 'w-auto opacity-100'">
                Pengguna
            </span>

            @if (request()->is('users*'))
                <span class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-blue-700 rounded-l"></span>
            @endif
        </a>

    </nav>

    {{-- Mobile Toggle Button (shows when sidebar hidden on mobile) --}}
    <button @click="sidebarOpen = true"
        class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 transition-colors"
        x-show="!sidebarOpen" x-transition>
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
</aside>
