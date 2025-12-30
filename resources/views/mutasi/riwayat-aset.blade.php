@extends('layouts.app')
@section('title', 'Riwayat Aset')
@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">

        {{-- Header Card --}}
        <div class="mb-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    {{-- Breadcrumb --}}
                    <div class="flex items-center gap-4">
                        <div class="flex flex-col">
                            @php
                                $from = request('from', 'index');

                                if ($from === 'index') {
                                    $breadcrumbs = [
                                        ['label' => 'Daftar Mutasi', 'url' => route('mutasi.index')],
                                        [
                                            'label' => 'Detail Mutasi',
                                            'url' => route('mutasi.show', [
                                                'mutasi' => $riwayat->first()->id ?? null,
                                                'from' => 'index',
                                            ]),
                                        ],
                                        ['label' => 'Riwayat Aset'],
                                    ];
                                } else {
                                    $breadcrumbs = [
                                        ['label' => 'Riwayat Mutasi', 'url' => route('mutasi.riwayat')],
                                        [
                                            'label' => 'Detail Mutasi',
                                            'url' => route('mutasi.show', [
                                                'mutasi' => $riwayat->first()->id ?? null,
                                                'from' => 'riwayat',
                                            ]),
                                        ],
                                        ['label' => 'Riwayat Aset'],
                                    ];
                                }
                            @endphp
                            <x-breadcrumbs.mutasi :items="$breadcrumbs" />
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('asets.show', $aset->id) }}"
                            class="inline-flex items-center h-9 px-4
               bg-blue-600 hover:bg-blue-700
               text-white rounded-lg text-sm font-medium
               transition-colors shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none">
                                <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2">
                                </path>
                                <rect x="9" y="3" width="6" height="4" rx="2"></rect>
                                <line x1="9" y1="12" x2="9.01" y2="12"></line>
                                <line x1="13" y1="12" x2="15" y2="12"></line>
                            </svg>
                            Detail Aset Lengkap
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Aset Card --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
            <div class="border-b border-gray-200 px-6 py-4">
                <div class="flex items-center gap-2">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Aset</h3>
                </div>
            </div>
            <div class="p-6">
                {{-- Nama Aset Highlighted --}}
                <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                    <p class="text-xs font-medium text-blue-600 uppercase mb-2">Nama Aset</p>
                    <p class="text-xl font-bold text-gray-900">{{ $aset->nama_jenis_barang }}</p>
                </div>

                {{-- Detail Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-2">
                            Kode Barang
                        </label>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" fill="none">
                                <path d="M4 7v-1a2 2 0 0 1 2 -2h2"></path>
                                <path d="M4 17v1a2 2 0 0 0 2 2h2"></path>
                                <path d="M16 4h2a2 2 0 0 1 2 2v1"></path>
                                <path d="M16 20h2a2 2 0 0 0 2 -2v-1"></path>
                                <rect x="5" y="11" width="1" height="2"></rect>
                                <line x1="10" y1="11" x2="10" y2="13"></line>
                                <rect x="14" y="11" width="1" height="2"></rect>
                                <line x1="19" y1="11" x2="19" y2="13"></line>
                            </svg>
                            <span class="font-mono text-sm font-medium text-gray-900">
                                {{ $aset->kode_barang }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-2">
                            Register
                        </label>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" fill="none">
                                <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2">
                                </path>
                                <rect x="9" y="3" width="6" height="4" rx="2"></rect>
                                <line x1="9" y1="12" x2="9.01" y2="12"></line>
                                <line x1="13" y1="12" x2="15" y2="12"></line>
                            </svg>
                            <span class="font-mono text-sm font-medium text-gray-900">
                                {{ $aset->register }}
                            </span>
                        </div>
                    </div>

                    @if ($aset->merk_type)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase mb-2">
                                Merk / Type
                            </label>
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" fill="none">
                                    <path d="M3 7l6 -3l6 3l6 -3v13l-6 3l-6 -3l-6 3v-13"></path>
                                    <line x1="9" y1="4" x2="9" y2="16"></line>
                                    <line x1="15" y1="7" x2="15" y2="19"></line>
                                </svg>
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $aset->merk_type }}
                                </span>
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-2">
                            Ruangan Saat Ini
                        </label>
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-blue-100 border border-blue-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-700" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" fill="none">
                                <path d="M5 12l-2 0l9 -9l9 9l-2 0"></path>
                                <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path>
                                <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"></path>
                            </svg>
                            <span class="text-sm font-semibold text-blue-700">
                                {{ $aset->ruangan ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Timeline Card --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <h3 class="text-lg font-semibold text-gray-900">Timeline Perpindahan</h3>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if ($riwayat->count() > 0)
                    {{-- Summary Banner --}}
                    <div
                        class="mb-6 rounded-lg bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" fill="none">
                                    <path
                                        d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2">
                                    </path>
                                    <rect x="9" y="3" width="6" height="4" rx="2"></rect>
                                    <line x1="9" y1="12" x2="9.01" y2="12"></line>
                                    <line x1="13" y1="12" x2="15" y2="12"></line>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-blue-900">
                                    Aset ini telah mengalami <strong class="text-lg">{{ $riwayat->total() }}</strong> kali
                                    perpindahan
                                </p>
                                <p class="text-xs text-blue-700 mt-1">
                                    Terakhir dimutasi pada
                                    <strong>{{ $riwayat->first()->tanggal_mutasi->format('d F Y') }}</strong>
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Timeline --}}
                    <div class="relative border-l-2 border-gray-200 ml-4 space-y-8">
                        @foreach ($riwayat as $index => $item)
                            <div class="relative pl-8 group">
                                {{-- Animated Dot --}}
                                <span
                                    class="absolute -left-[11px] top-2 w-5 h-5 rounded-full 
                                           {{ $index === 0 ? 'bg-green-600 ring-4 ring-green-100' : 'bg-blue-600' }} 
                                           transition-all duration-300 group-hover:scale-125"></span>

                                {{-- Card --}}
                                <div
                                    class="bg-gray-50 rounded-xl border border-gray-200 p-5 
                                        transition-all duration-300 hover:shadow-md hover:border-blue-300">
                                    <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">

                                        {{-- Left Content --}}
                                        <div class="flex-1 space-y-3">
                                            {{-- Header Info --}}
                                            <div class="flex flex-wrap items-center gap-3">
                                                <div
                                                    class="flex items-center gap-2 px-3 py-1 rounded-lg bg-white border border-gray-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="w-3.5 h-3.5 text-gray-500" viewBox="0 0 24 24"
                                                        stroke-width="2" stroke="currentColor" fill="none">
                                                        <rect x="4" y="5" width="16" height="16" rx="2">
                                                        </rect>
                                                        <line x1="16" y1="3" x2="16"
                                                            y2="7"></line>
                                                        <line x1="8" y1="3" x2="8"
                                                            y2="7"></line>
                                                        <line x1="4" y1="11" x2="20"
                                                            y2="11"></line>
                                                    </svg>
                                                    <span class="text-xs font-medium text-gray-700">
                                                        {{ $item->tanggal_mutasi->format('d M Y') }}
                                                    </span>
                                                </div>

                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-200 text-xs font-mono">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="w-3.5 h-3.5 text-gray-600" viewBox="0 0 24 24"
                                                        stroke-width="2" stroke="currentColor" fill="none">
                                                        <path
                                                            d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2">
                                                        </path>
                                                        <rect x="9" y="3" width="6" height="4" rx="2">
                                                        </rect>
                                                    </svg>
                                                    {{ $item->nomor_surat }}
                                                </span>

                                                @if ($index === 0)
                                                    <span
                                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-medium">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3"
                                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                            fill="none">
                                                            <path d="M5 12l5 5l10 -10"></path>
                                                        </svg>
                                                        Terbaru
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- Perpindahan Ruangan --}}
                                            <div class="flex flex-wrap items-center gap-2">
                                                <div
                                                    class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-red-50 border border-red-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600"
                                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                        fill="none">
                                                        <path d="M5 12l-2 0l9 -9l9 9l-2 0"></path>
                                                        <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path>
                                                        <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"></path>
                                                    </svg>
                                                    <span class="text-sm font-semibold text-red-700">
                                                        {{ $item->ruangan_asal }}
                                                    </span>
                                                </div>

                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                    fill="none">
                                                    <line x1="5" y1="12" x2="19" y2="12">
                                                    </line>
                                                    <line x1="15" y1="16" x2="19" y2="12">
                                                    </line>
                                                    <line x1="15" y1="8" x2="19" y2="12">
                                                    </line>
                                                </svg>

                                                <div
                                                    class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-green-50 border border-green-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-600"
                                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                        fill="none">
                                                        <path d="M5 12l-2 0l9 -9l9 9l-2 0"></path>
                                                        <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path>
                                                        <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"></path>
                                                    </svg>
                                                    <span class="text-sm font-semibold text-green-700">
                                                        {{ $item->ruangan_tujuan }}
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- Lokasi Detail --}}
                                            @if ($item->lokasi_asal || $item->lokasi_tujuan)
                                                <div class="flex items-center gap-2 text-xs text-gray-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5"
                                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                        fill="none">
                                                        <circle cx="12" cy="11" r="3"></circle>
                                                        <path
                                                            d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z">
                                                        </path>
                                                    </svg>
                                                    <span>{{ $item->lokasi_asal ?: '-' }}</span>
                                                    <span>→</span>
                                                    <span>{{ $item->lokasi_tujuan ?: '-' }}</span>
                                                </div>
                                            @endif

                                            {{-- User Info --}}
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="w-7 h-7 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                                    <span class="text-white font-semibold text-xs">
                                                        {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div class="text-xs text-gray-600">
                                                    <span class="font-medium">{{ $item->user->name }}</span>
                                                    <span class="mx-1">•</span>
                                                    <span>{{ $item->created_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                            </div>

                                            {{-- Keterangan --}}
                                            @if ($item->keterangan)
                                                <div class="pt-3 border-t border-gray-200">
                                                    <div class="flex items-start gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0"
                                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                            fill="none">
                                                            <path d="M12 8h.01"></path>
                                                            <path d="M11 12h1v4h1"></path>
                                                            <circle cx="12" cy="12" r="9"></circle>
                                                        </svg>
                                                        <p class="text-sm text-gray-700 italic">
                                                            "{{ $item->keterangan }}"
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Action Buttons --}}
                                        <div class="flex gap-2 self-start flex-shrink-0">
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
                                                        <line x1="9" y1="9" x2="10"
                                                            y2="9">
                                                        </line>
                                                        <line x1="9" y1="13" x2="15"
                                                            y2="13">
                                                        </line>
                                                        <line x1="9" y1="17" x2="15"
                                                            y2="17">
                                                        </line>
                                                    </svg>
                                                    PDF
                                                </a>
                                            @endif
                                            <a href="{{ route('mutasi.show', ['mutasi' => $item->id, 'from' => $from]) }}"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200 text-xs font-medium">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                    fill="none">
                                                    <circle cx="12" cy="12" r="2"></circle>
                                                    <path d="M22 12c-2.667 4 -6 6 -10 6s-7.333 -2 -10 -6
                                                                                   c2.667 -4 6 -6 10 -6s7.333 2 10 6">
                                                    </path>
                                                </svg>
                                                Detail
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if ($riwayat->hasPages())
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            {{ $riwayat->links() }}
                        </div>
                    @endif
                @else
                    {{-- Empty State --}}
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" fill="none">
                                <circle cx="12" cy="12" r="9"></circle>
                                <polyline points="12 8 12 12 14 14"></polyline>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Riwayat Mutasi</h3>
                        <p class="text-sm text-gray-500">
                            Aset ini belum pernah mengalami perpindahan ruangan
                        </p>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection

@push('page-styles')
    <style>
        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }

        /* Timeline hover effects */
        .group:hover .absolute {
            transform: scale(1.1);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
@endpush
