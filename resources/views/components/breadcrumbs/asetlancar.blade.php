@props([
    'current' => null, // label halaman aktif (Tambah Aset, Detail, Edit, dll)
])

<nav class="flex items-center text-sm font-medium text-gray-600">

    {{-- Level 1 : Aset (SELALU DEFAULT, TIDAK LINK) --}}
    <span class="text-gray-600">
        Aset
    </span>

    {{-- Separator --}}
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd"
            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 0 01-1.414 0z"
            clip-rule="evenodd" />
    </svg>

    {{-- Level 2 : Aset Lancar --}}
    @if (request()->routeIs('aset-lancars.index'))
        <span class="text-blue-600 font-semibold">
            Aset Lancar
        </span>
    @else
        <a href="{{ route('aset-lancars.index') }}"
            class="text-gray-600 hover:text-blue-600 transition-colors">
            Aset Lancar
        </a>
    @endif

    {{-- Level 3 : Current Page (Tambah / Detail / Edit) --}}
    @if ($current)
        <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 0 01-1.414 0z"
                clip-rule="evenodd" />
        </svg>

        <span class="text-blue-600 font-semibold">
            {{ $current }}
        </span>
    @endif

</nav>
