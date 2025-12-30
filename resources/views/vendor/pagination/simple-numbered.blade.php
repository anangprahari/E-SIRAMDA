@if ($paginator->hasPages())
    <nav role="navigation" class="flex items-center justify-between">
        {{-- Mobile --}}
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 text-sm text-gray-400 bg-gray-100 border border-gray-300 rounded-md">
                    ‹
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="px-4 py-2 text-sm text-blue-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    ‹
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="ml-3 px-4 py-2 text-sm text-blue-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    ›
                </a>
            @else
                <span class="ml-3 px-4 py-2 text-sm text-gray-400 bg-gray-100 border border-gray-300 rounded-md">
                    ›
                </span>
            @endif
        </div>

        {{-- Desktop --}}
        <div class="hidden sm:flex sm:items-center sm:justify-end gap-1">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 text-sm text-gray-400 border rounded-md">‹</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="px-3 py-2 text-sm text-blue-600 border rounded-md hover:bg-blue-50">
                    ‹
                </a>
            @endif

            {{-- Page 1 --}}
            <a href="{{ $paginator->url(1) }}"
                class="px-3 py-2 text-sm border rounded-md
               {{ $paginator->currentPage() == 1 ? 'bg-blue-600 text-white' : 'text-blue-600 hover:bg-blue-50' }}">
                1
            </a>

            {{-- Page 2 & 3 --}}
            @for ($i = 2; $i <= min(3, $paginator->lastPage()); $i++)
                <a href="{{ $paginator->url($i) }}"
                    class="px-3 py-2 text-sm border rounded-md
                   {{ $paginator->currentPage() == $i ? 'bg-blue-600 text-white' : 'text-blue-600 hover:bg-blue-50' }}">
                    {{ $i }}
                </a>
            @endfor

            {{-- Ellipsis --}}
            @if ($paginator->lastPage() > 4)
                <span class="px-3 py-2 text-sm text-gray-500">…</span>
            @endif

            {{-- Last Page --}}
            @if ($paginator->lastPage() > 3)
                <a href="{{ $paginator->url($paginator->lastPage()) }}"
                    class="px-3 py-2 text-sm border rounded-md
                   {{ $paginator->currentPage() == $paginator->lastPage()
                       ? 'bg-blue-600 text-white'
                       : 'text-blue-600 hover:bg-blue-50' }}">
                    {{ $paginator->lastPage() }}
                </a>
            @endif

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="px-3 py-2 text-sm text-blue-600 border rounded-md hover:bg-blue-50">
                    ›
                </a>
            @else
                <span class="px-3 py-2 text-sm text-gray-400 border rounded-md">›</span>
            @endif
        </div>
    </nav>
@endif
