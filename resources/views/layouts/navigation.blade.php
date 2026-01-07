{{-- Navigation bar - menyesuaikan dengan lebar sidebar --}}
<nav class="fixed top-0 right-0 h-16 bg-white border-b border-gray-200 z-20 shadow-sm transition-all duration-300"
    :class="sidebarCollapsed ? 'left-0 lg:left-[72px]' : 'left-0 lg:left-[240px]'">

    <div class="h-full px-4 sm:px-6 lg:px-8 flex items-center justify-between">

        {{-- Left Section : Mobile Button + App Description --}}
        <div class="flex items-center gap-3">

            {{-- Mobile Menu Button --}}
            <button @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <div class="hidden sm:flex flex-col leading-tight select-none">
                <span class="text-lg font-semibold text-gray-800 tracking-wide">
                    Elektronik Sistem Informasi
                </span>
                <span class="text-sm text-gray-500">
                    Inventaris Barang Milik Daerah
                </span>
            </div>
        </div>

        {{-- Spacer --}}
        <div class="flex-1"></div>

        {{-- User Dropdown --}}
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button
                    class="flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition-all duration-200 group">
                    <div class="flex items-center gap-2">
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-xs shadow-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <span class="hidden sm:inline font-medium">
                            {{ Auth::user()->name }}
                        </span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-y-0.5"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <div class="py-1">

                    <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profil
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="flex items-center gap-2 text-red-600 hover:bg-red-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </x-dropdown-link>
                    </form>

                </div>
            </x-slot>
        </x-dropdown>

    </div>
</nav>
