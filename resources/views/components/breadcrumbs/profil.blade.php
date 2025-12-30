@props(['current' => null])

<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        {{-- Home --}}
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">
                Dashboard
            </a>
        </li>

        {{-- Profil --}}
        @if ($current)
            <li>
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('profile.edit') }}"
                        class="ml-1 text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors md:ml-2">
                        Profil
                    </a>
                </div>
            </li>

            {{-- Current Page --}}
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-blue-600 md:ml-2">{{ $current }}</span>
                </div>
            </li>
        @else
            {{-- Profil as current page --}}
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-blue-600 md:ml-2">Profil</span>
                </div>
            </li>
        @endif
    </ol>
</nav>