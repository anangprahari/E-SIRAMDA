@extends('layouts.app')
@section('title', 'Riwayat Mutasi')
@section('content')
    <div class="container mx-auto px-4 py-6">

        {{-- Header Card --}}
        <div class="mb-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    {{-- Left : Breadcrumb --}}
                    <div class="flex items-center gap-4">
                        <div class="flex flex-col">
                            <x-breadcrumbs.mutasi :items="[['label' => 'Riwayat Mutasi']]" />
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Filter Card --}}
        <div class="bg-white rounded-xl shadow-sm mb-4 border border-gray-200">
            <div class="p-4">
                <form action="{{ route('mutasi.riwayat') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-3 items-end">

                        {{-- Pencarian --}}
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                Pencarian
                            </label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nomor surat atau nama aset..."
                                class="w-full h-9 rounded-lg border-gray-300 text-sm px-3
                               focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Ruangan --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                Ruangan
                            </label>
                            <select name="ruangan"
                                class="w-full h-9 rounded-lg border-gray-300 text-sm px-2
                               focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Ruangan</option>
                                @foreach ($ruanganOptions as $ruangan)
                                    <option value="{{ $ruangan }}"
                                        {{ request('ruangan') == $ruangan ? 'selected' : '' }}>
                                        {{ $ruangan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tanggal Dari --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                Tanggal Dari
                            </label>
                            <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                                class="w-full h-9 rounded-lg border-gray-300 text-sm px-2
                               focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Tanggal Sampai --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                Tanggal Sampai
                            </label>
                            <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                                class="w-full h-9 rounded-lg border-gray-300 text-sm px-2
                               focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- ACTION --}}
                        <div class="flex gap-2">
                            <button type="submit"
                                class="h-9 px-8 rounded-lg bg-blue-600 text-white text-sm
                               hover:bg-blue-700 transition">
                                Filter
                            </button>

                            <a href="{{ route('mutasi.riwayat') }}"
                                class="h-9 px-8 rounded-lg border text-sm text-gray-600
                               hover:bg-gray-100 transition flex items-center">
                                Reset
                            </a>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- Main Table Card --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-blue-100 via-blue-50 to-blue-100 border-b border-blue-200 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg flex items-center justify-center shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" fill="none">
                            <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2">
                            </path>
                            <rect x="9" y="3" width="6" height="4" rx="2"></rect>
                            <line x1="9" y1="12" x2="9.01" y2="12"></line>
                            <line x1="13" y1="12" x2="15" y2="12"></line>
                            <line x1="9" y1="16" x2="9.01" y2="16"></line>
                            <line x1="13" y1="16" x2="15" y2="16"></line>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-blue-800">Riwayat Mutasi Aset</h3>
                        <p class="text-sm text-blue-600">
                            Total <span class="font-bold">{{ $riwayat->total() }}</span> riwayat mutasi ditemukan
                        </p>
                    </div>
                </div>
            </div>
            {{-- Entries Per Page (DI ATAS KOLOM TABEL) --}}
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between
            px-6 py-3 border-b border-gray-200 bg-gray-50">

                <form method="GET" action="{{ route('mutasi.index') }}" class="flex items-center gap-2">

                    {{-- Pertahankan filter --}}
                    @foreach (request()->except(['per_page', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <span class="text-sm text-gray-600">Tampilkan</span>

                    <select name="per_page" onchange="this.form.submit()"
                        class="h-8 min-w-[4rem] px-3
                   rounded-md border border-gray-300
                   text-sm text-gray-700 bg-white
                   focus:ring-blue-500 focus:border-blue-500">
                        @foreach ([5, 10, 20, 50] as $size)
                            <option value="{{ $size }}" @selected(request('per_page', 10) == $size)>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>

                    <span class="text-sm text-gray-600">data</span>
                </form>

                <div class="text-sm text-gray-600 mt-2 sm:mt-0">
                    Menampilkan
                    <span class="font-semibold">{{ $riwayat->firstItem() ?? 0 }}</span>
                    –
                    <span class="font-semibold">{{ $riwayat->lastItem() ?? 0 }}</span>
                    dari
                    <span class="font-semibold">{{ $riwayat->total() }}</span>
                    data
                </div>
            </div>

            {{-- Scroll Atas --}}
            <div id="scroll-top" class="overflow-x-auto h-4 mb-2 custom-scrollbar">
                <div id="scroll-top-inner" class="h-4"></div>
            </div>

            {{-- Table --}}
            <div id="scroll-table" class="overflow-x-auto custom-scrollbar scrollbar-hidden">
                <table class="w-full min-w-max text-sm">
                    <thead class="bg-gradient-to-r from-blue-600 to-blue-700 text-white sticky top-0 z-10">
                        <tr>
                            <th class="px-5 py-3 text-center font-semibold uppercase tracking-wider whitespace-nowrap">No
                            </th>
                            <th class="px-5 py-3 text-left font-semibold uppercase tracking-wider whitespace-nowrap">
                                Tanggal</th>
                            <th class="px-5 py-3 text-left font-semibold uppercase tracking-wider whitespace-nowrap">No.
                                Surat</th>
                            <th class="px-5 py-3 text-left font-semibold uppercase tracking-wider whitespace-nowrap">Aset
                            </th>
                            <th class="px-5 py-3 text-left font-semibold uppercase tracking-wider whitespace-nowrap">
                                Perpindahan</th>
                            <th class="px-5 py-3 text-left font-semibold uppercase tracking-wider whitespace-nowrap">
                                Diajukan Oleh</th>
                            <th class="px-5 py-3 text-center font-semibold uppercase tracking-wider whitespace-nowrap">
                                Berita Acara</th>
                            <th class="px-5 py-3 text-center font-semibold uppercase tracking-wider whitespace-nowrap">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($riwayat as $index => $item)
                            <tr
                                class="hover:bg-blue-50 transition-colors duration-200 {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                <td class="px-5 py-4 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-700 rounded-lg font-semibold text-xs">
                                        {{ ($riwayat->currentPage() - 1) * $riwayat->perPage() + $index + 1 }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-gray-700 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        {{ $item->tanggal_mutasi->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="px-5 py-4 font-medium text-gray-900 font-mono text-xs whitespace-nowrap">
                                    {{ $item->nomor_surat }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-800">{{ $item->aset->nama_jenis_barang }}</div>
                                    <div class="text-xs text-gray-500 font-mono">
                                        {{ $item->aset->kode_barang }} • Reg {{ $item->aset->register }}
                                    </div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span
                                            class="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                            {{ $item->ruangan_asal }}
                                        </span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                            <line x1="15" y1="16" x2="19" y2="12"></line>
                                            <line x1="15" y1="8" x2="19" y2="12"></line>
                                        </svg>
                                        <span
                                            class="inline-flex px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                            {{ $item->ruangan_tujuan }}
                                        </span>
                                    </div>
                                    @if ($item->lokasi_asal || $item->lokasi_tujuan)
                                        <div class="text-xs text-gray-500 flex items-center gap-2">
                                            <span>{{ $item->lokasi_asal ?: '-' }}</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                fill="none">
                                                <line x1="5" y1="12" x2="19" y2="12">
                                                </line>
                                                <line x1="15" y1="16" x2="19" y2="12">
                                                </line>
                                                <line x1="15" y1="8" x2="19" y2="12">
                                                </line>
                                            </svg>
                                            <span>{{ $item->lokasi_tujuan ?: '-' }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-800">{{ $item->user->name }}</div>
                                    <div class="text-xs text-gray-500 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" fill="none">
                                            <circle cx="12" cy="12" r="9"></circle>
                                            <polyline points="12 7 12 12 15 15"></polyline>
                                        </svg>
                                        {{ $item->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    @if ($item->berita_acara_path)
                                        <a href="{{ route('mutasi.download', $item->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors duration-200 text-xs font-medium">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                fill="none">
                                                <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                                <path
                                                    d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z">
                                                </path>
                                                <line x1="9" y1="9" x2="10" y2="9">
                                                </line>
                                                <line x1="9" y1="13" x2="15" y2="13">
                                                </line>
                                                <line x1="9" y1="17" x2="15" y2="17">
                                                </line>
                                            </svg>
                                            PDF
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <a href="{{ route('mutasi.show', ['mutasi' => $item->id, 'from' => 'riwayat']) }}"
                                        class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200 text-xs font-medium">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" fill="none">
                                            <circle cx="12" cy="12" r="2"></circle>
                                            <path
                                                d="M22 12c-2.667 4 -6 6 -10 6s-7.333 -2 -10 -6c2.667 -4 6 -6 10 -6s7.333 2 10 6">
                                            </path>
                                        </svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-16">
                                    <div class="text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-24 h-24 mx-auto text-blue-300 mb-4" viewBox="0 0 24 24"
                                            stroke-width="1" stroke="currentColor" fill="none">
                                            <path
                                                d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2">
                                            </path>
                                            <rect x="9" y="3" width="6" height="4" rx="2"></rect>
                                            <line x1="9" y1="12" x2="9.01" y2="12"></line>
                                            <line x1="13" y1="12" x2="15" y2="12"></line>
                                            <line x1="9" y1="16" x2="9.01" y2="16"></line>
                                            <line x1="13" y1="16" x2="15" y2="16"></line>
                                        </svg>
                                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak ada riwayat mutasi</h3>
                                        @if (request()->hasAny(['search', 'ruangan', 'tanggal_dari', 'tanggal_sampai']))
                                            <p class="text-gray-500 mb-4">Tidak ditemukan data dengan filter yang dipilih
                                            </p>
                                            <a href="{{ route('mutasi.riwayat') }}"
                                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl font-medium">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                    fill="none">
                                                    <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"></path>
                                                    <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"></path>
                                                </svg>
                                                Tampilkan Semua Data
                                            </a>
                                        @else
                                            <p class="text-gray-500">Belum ada riwayat mutasi yang tercatat</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            @if ($riwayat->hasPages())
                <div class="bg-gradient-to-r from-blue-100 via-blue-50 to-blue-100 border-t border-blue-200 px-6 py-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center text-sm text-blue-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" fill="none">
                                <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2">
                                </path>
                                <rect x="9" y="3" width="6" height="4" rx="2"></rect>
                            </svg>
                            <span>
                                Menampilkan <span class="font-bold text-blue-800">{{ $riwayat->firstItem() }}</span>
                                sampai <span class="font-bold text-blue-800">{{ $riwayat->lastItem() }}</span>
                                dari <span class="font-bold text-blue-800">{{ $riwayat->total() }}</span> data
                            </span>
                        </div>
                        <div class="flex justify-center sm:justify-end">
                            {{ $mutasi->links('vendor.pagination.simple-numbered') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('page-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sync scroll between top scrollbar and table
            const topScroll = document.getElementById('scroll-top');
            const tableScroll = document.getElementById('scroll-table');
            const topInner = document.getElementById('scroll-top-inner');

            function syncWidth() {
                topInner.style.width = tableScroll.scrollWidth + 'px';
            }

            syncWidth();
            window.addEventListener('resize', syncWidth);

            topScroll.addEventListener('scroll', () => {
                tableScroll.scrollLeft = topScroll.scrollLeft;
            });

            tableScroll.addEventListener('scroll', () => {
                topScroll.scrollLeft = tableScroll.scrollLeft;
            });
        });

        $(document).ready(function() {
            // Smooth scroll on pagination
            $('.pagination a').on('click', function() {
                $('html, body').animate({
                    scrollTop: 0
                }, 500);
            });
        });
    </script>
@endpush

@push('page-styles')
    <style>
        .custom-scrollbar {
            scrollbar-color: #3b82f6 #f1f5f9;
            scrollbar-width: auto;
        }

        /* Firefox */
        .custom-scrollbar {
            scrollbar-color: #3b82f6 #f1f5f9;
        }

        .scrollbar-hidden::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hidden {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        #scroll-top {
            padding-right: 12px;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
@endpush
