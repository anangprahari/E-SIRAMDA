@extends('layouts.app')
@section('title', 'Detail Mutasi')
@section('content')
    <div class="container mx-auto px-4 py-6">

        {{-- Header Card --}}
        <div class="mb-6">
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    {{-- Breadcrumb --}}
                    <div class="flex items-center gap-4">
                        <div class="flex flex-col">
                            @php
                                $from = request('from');

                                if ($from === 'index') {
                                    $breadcrumbs = [
                                        ['label' => 'Daftar Mutasi', 'url' => route('mutasi.index')],
                                        ['label' => 'Detail Mutasi'],
                                    ];
                                } elseif ($from === 'riwayat') {
                                    $breadcrumbs = [
                                        ['label' => 'Riwayat Mutasi', 'url' => route('mutasi.riwayat')],
                                        ['label' => 'Detail Mutasi'],
                                    ];
                                } else {
                                    $breadcrumbs = [
                                        ['label' => 'Daftar Mutasi', 'url' => route('mutasi.index')],
                                        ['label' => 'Detail Mutasi'],
                                    ];
                                }
                            @endphp

                            <x-breadcrumbs.mutasi :items="$breadcrumbs" />
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-wrap items-center gap-2">

                        {{-- Riwayat Aset --}}
                        <a href="{{ route('mutasi.riwayat.aset', [
                            'aset' => $mutasi->aset->id,
                            'from' => request('from', 'index'),
                        ]) }}"
                            class="inline-flex items-center h-9 px-4
               bg-purple-600 text-white rounded-lg text-sm font-medium
               hover:bg-purple-700 transition-colors shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none">
                                <circle cx="12" cy="12" r="9"></circle>
                                <polyline points="12 8 12 12 14 14"></polyline>
                            </svg>
                            Riwayat Aset
                        </a>

                        @if ($mutasi->berita_acara_path)
                            {{-- Preview Berita Acara --}}
                            <a href="{{ route('mutasi.preview', $mutasi->id) }}" target="_blank"
                                class="inline-flex items-center h-9 px-4
                   bg-blue-600 text-white rounded-lg text-sm font-medium
                   hover:bg-blue-700 transition-colors shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" fill="none">
                                    <circle cx="12" cy="12" r="2"></circle>
                                    <path d="M22 12c-2.667 4 -6 6 -10 6
                             s-7.333 -2 -10 -6
                             c2.667 -4 6 -6 10 -6
                             s7.333 2 10 6"></path>
                                </svg>
                                Lihat Berita Acara
                            </a>

                            {{-- Download Berita Acara --}}
                            <a href="{{ route('mutasi.download', $mutasi->id) }}"
                                class="inline-flex items-center h-9 px-4
                   bg-yellow-500 text-white rounded-lg text-sm font-medium
                   hover:bg-yellow-600 transition-colors shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" fill="none">
                                    <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"></path>
                                    <polyline points="7 11 12 16 17 11"></polyline>
                                    <line x1="12" y1="4" x2="12" y2="16"></line>
                                </svg>
                                Download Berita Acara
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Success Alert --}}
        @if (session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-lg p-4 flex items-start animate-fade-in"
                role="alert">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" fill="none">
                        <path d="M5 12l5 5l10 -10"></path>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
                <button type="button" class="ml-3 flex-shrink-0 text-green-600 hover:text-green-800"
                    onclick="this.parentElement.remove()">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Main Content --}}
        <div class="space-y-6">

            {{-- Status Banner --}}
            <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" fill="none">
                            <path d="M5 12l5 5l10 -10"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-green-900 mb-1">Mutasi Berhasil Dilakukan</h3>
                        <p class="text-sm text-green-800">
                            Aset <strong>{{ $mutasi->aset->nama_jenis_barang }}</strong> telah dipindahkan dari
                            <strong>{{ $mutasi->ruangan_asal }}</strong> ke <strong>{{ $mutasi->ruangan_tujuan }}</strong>
                            pada tanggal <strong>{{ $mutasi->tanggal_mutasi->format('d F Y') }}</strong>.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Informasi Mutasi Card --}}
            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Mutasi</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-2">
                                    Nomor Surat
                                </label>
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                        <path
                                            d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2">
                                        </path>
                                        <rect x="9" y="3" width="6" height="4" rx="2"></rect>
                                    </svg>
                                    <span class="font-mono text-sm font-medium text-gray-900">
                                        {{ $mutasi->nomor_surat }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-2">
                                    Diajukan Oleh
                                </label>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">
                                            {{ strtoupper(substr($mutasi->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <p class="font-medium text-gray-900">{{ $mutasi->user->name }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-2">
                                    Tanggal Mutasi
                                </label>
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                        <rect x="4" y="5" width="16" height="16" rx="2"></rect>
                                        <line x1="16" y1="3" x2="16" y2="7"></line>
                                        <line x1="8" y1="3" x2="8" y2="7"></line>
                                        <line x1="4" y1="11" x2="20" y2="11"></line>
                                    </svg>
                                    <span class="font-medium text-gray-900">
                                        {{ $mutasi->tanggal_mutasi->format('d F Y') }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-2">
                                    Tanggal Input
                                </label>
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <polyline points="12 7 12 12 15 15"></polyline>
                                    </svg>
                                    <span class="text-sm text-gray-600">
                                        {{ $mutasi->created_at->format('d/m/Y H:i') }} WIB
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($mutasi->keterangan)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <label class="block text-xs font-medium text-gray-500 uppercase mb-2">
                                Keterangan
                            </label>
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <p class="text-sm text-gray-700">{{ $mutasi->keterangan }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Informasi Aset Card --}}
            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Aset</h3>
                </div>
                <div class="p-6">
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-xs font-medium text-gray-500 uppercase mb-2">Nama Aset</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $mutasi->aset->nama_jenis_barang }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                                    {{ $mutasi->aset->kode_barang }}
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
                                    <path
                                        d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2">
                                    </path>
                                    <rect x="9" y="3" width="6" height="4" rx="2"></rect>
                                    <line x1="9" y1="12" x2="9.01" y2="12"></line>
                                    <line x1="13" y1="12" x2="15" y2="12"></line>
                                </svg>
                                <span class="font-mono text-sm font-medium text-gray-900">
                                    {{ $mutasi->aset->register }}
                                </span>
                            </div>
                        </div>
                        @if ($mutasi->aset->merk_type)
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-2">
                                    Merk / Type
                                </label>
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                        <path d="M3 7l6 -3l6 3l6 -3v13l-6 3l-6 -3l-6 3v-13"></path>
                                        <line x1="9" y1="4" x2="9" y2="16"></line>
                                        <line x1="15" y1="7" x2="15" y2="19"></line>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $mutasi->aset->merk_type }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Perpindahan Card --}}
            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Perpindahan Ruangan & Lokasi</h3>
                </div>
                <div class="p-6">
                    {{-- Perpindahan Ruangan --}}
                    <div class="mb-6">
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-4">
                            Perpindahan Ruangan
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <div class="p-4 rounded-lg bg-red-50 border border-red-200">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                        <path d="M5 12l-2 0l9 -9l9 9l-2 0"></path>
                                        <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path>
                                        <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"></path>
                                    </svg>
                                    <p class="text-xs font-medium text-red-600 uppercase">Ruangan Asal</p>
                                </div>
                                <p class="text-base font-semibold text-red-700">{{ $mutasi->ruangan_asal }}</p>
                            </div>

                            <div class="flex justify-center">
                                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <line x1="15" y1="16" x2="19" y2="12"></line>
                                        <line x1="15" y1="8" x2="19" y2="12"></line>
                                    </svg>
                                </div>
                            </div>

                            <div class="p-4 rounded-lg bg-green-50 border border-green-200">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-600"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                        <path d="M5 12l-2 0l9 -9l9 9l-2 0"></path>
                                        <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path>
                                        <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"></path>
                                    </svg>
                                    <p class="text-xs font-medium text-green-600 uppercase">Ruangan Tujuan</p>
                                </div>
                                <p class="text-base font-semibold text-green-700">{{ $mutasi->ruangan_tujuan }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Perpindahan Lokasi --}}
                    @if ($mutasi->lokasi_asal || $mutasi->lokasi_tujuan)
                        <div class="pt-6 border-t border-gray-200">
                            <label class="block text-xs font-medium text-gray-500 uppercase mb-4">
                                Perpindahan Lokasi Detail
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                <div class="p-4 rounded-lg bg-gray-50 border border-gray-200">
                                    <p class="text-xs text-gray-500 mb-1">Lokasi Asal</p>
                                    <p class="font-medium text-gray-900">
                                        {{ $mutasi->lokasi_asal ?: 'Tidak tercatat' }}
                                    </p>
                                </div>

                                <div class="flex justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <line x1="15" y1="16" x2="19" y2="12"></line>
                                        <line x1="15" y1="8" x2="19" y2="12"></line>
                                    </svg>
                                </div>

                                <div class="p-4 rounded-lg bg-gray-50 border border-gray-200">
                                    <p class="text-xs text-gray-500 mb-1">Lokasi Tujuan</p>
                                    <p class="font-medium text-gray-900">
                                        {{ $mutasi->lokasi_tujuan ?: 'Tidak berubah' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-scripts')
    <script>
        $(document).ready(function() {
            // Auto dismiss alerts
            setTimeout(function() {
                $('.animate-fade-in').fadeOut('slow');
            }, 5000);
        });
    </script>
@endpush

@push('page-styles')
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>
@endpush
