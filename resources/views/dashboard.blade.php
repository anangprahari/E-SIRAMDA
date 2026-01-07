@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-6">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-center">

                {{-- KIRI : GAMBAR (NO BACKGROUND, LEBIH BESAR) --}}
                <div class="lg:col-span-1 flex justify-center lg:justify-start">
                    <img src="{{ asset('assets/img/dashboard.png') }}" alt="E-SIRAMDA"
                        class="max-h-56 lg:max-h-64 w-auto object-contain select-none">
                </div>
                <div class="lg:col-span-3">
                    <h1 class="text-xl lg:text-2xl font-bold text-gray-800 mb-3 leading-snug">
                        Selamat Datang
                        <span class="text-blue-600">
                            {{ auth()->user()->name }}
                        </span>
                        <br class="hidden sm:block">
                        di <span class="font-extrabold">E-SIRAMDA</span>
                        <span class="text-gray-700 font-semibold">
                            Diskominfo Provinsi Jambi
                        </span>
                    </h1>
                    <p class="text-sm lg:text-base text-gray-600 leading-relaxed max-w-4xl">
                        E-SIRAMDA merupakan sistem internal yang digunakan untuk pengelolaan inventaris
                        Barang Milik Daerah secara terintegrasi. Sistem ini mendukung pencatatan aset,
                        pengelolaan mutasi, serta penyusunan dan pencetakan laporan inventaris secara
                        akurat dan tertib.
                    </p>
                </div>
            </div>
        </div>

        {{-- ========================================
            2. KPI CARDS - 2 PILAR UTAMA
        ======================================== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Aset Tetap --}}
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Aset Tetap</p>
                        <p class="text-xs text-gray-500">Total Unit</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-end justify-between mb-3">
                    <h2 class="text-3xl font-bold text-gray-800">{{ number_format($totalAsetTetap) }}</h2>
                    <span class="text-sm text-gray-500">unit</span>
                </div>
                <div class="pt-3 border-t border-gray-100">
                    <p class="text-xs text-gray-600">Total Nilai: <span class="font-semibold text-blue-600">Rp
                            {{ number_format($totalNilaiAsetTetap, 0, ',', '.') }}</span></p>
                </div>
            </div>

            {{-- Mutasi Aset --}}
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Mutasi Aset</p>
                        <p class="text-xs text-gray-500">Total Perpindahan</p>
                    </div>
                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-end justify-between mb-3">
                    <h2 class="text-3xl font-bold text-gray-800">{{ number_format($totalMutasi) }}</h2>
                    <span class="text-sm text-gray-500">mutasi</span>
                </div>
                <div class="pt-3 border-t border-gray-100">
                    <p class="text-xs text-gray-600">Bulan ini: <span
                            class="font-semibold text-amber-600">{{ $mutasiBulanIni }} mutasi</span></p>
                </div>
            </div>
        </div>

        {{-- ========================================
            2.5. SECONDARY KPI - ASET KOPTABLE
        ======================================== --}}
        <div class="bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden">
            <div class="bg-gradient-to-r from-amber-50 to-yellow-50 border-b border-amber-200 px-5 py-3">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-base font-bold text-gray-800 mb-1">Aset Koptable (Kapitalisasi Tabel)</h3>
                        <p class="text-xs text-gray-600">Aset bernilai di bawah batas kapitalisasi (< Rp
                                {{ number_format($batasKoptable, 0, ',', '.') }}), perlu pengendalian dan monitoring
                                khusus.</p>
                    </div>
                </div>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Total Unit Koptable --}}
                    <div class="bg-gradient-to-br from-amber-50 to-white rounded-lg p-4 border border-amber-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-gray-600 font-medium">Total Unit</span>
                        </div>
                        <div class="flex items-end justify-between">
                            <span class="text-2xl font-bold text-gray-800">{{ number_format($totalAsetKoptable) }}</span>
                            <span class="text-xs text-gray-500">unit</span>
                        </div>
                    </div>

                    {{-- Total Nilai Koptable --}}
                    <div class="bg-gradient-to-br from-amber-50 to-white rounded-lg p-4 border border-amber-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-gray-600 font-medium">Total Nilai</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-gray-800">Rp
                                {{ number_format($totalNilaiAsetKoptable / 1000000, 1) }}jt</span>
                            <span class="text-xs text-gray-500 mt-1">Rp
                                {{ number_format($totalNilaiAsetKoptable, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================
            3. GRAFIK KONDISI ASET TETAP
        ======================================== --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-gray-800">Kondisi Aset Tetap</h3>
                <span class="text-xs text-gray-500">{{ number_format($totalAsetTetap) }} unit</span>
            </div>

            <div class="flex flex-col items-center justify-center py-2">
                {{-- Donut Chart --}}
                <div class="relative w-48 h-48">
                    <svg class="transform -rotate-90" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="54" fill="none" stroke="#f3f4f6" stroke-width="12" />
                        @php
                            $total = $kondisiAsetData['B'] + $kondisiAsetData['KB'] + $kondisiAsetData['RB'];
                            $circumference = 2 * 3.14159 * 54;
                            $offset = 0;
                        @endphp
                        @if ($total > 0)
                            @php
                                $baik_persen = $kondisiAsetData['B'] / $total;
                                $baik_length = $baik_persen * $circumference;
                            @endphp
                            <circle cx="60" cy="60" r="54" fill="none" stroke="#10b981" stroke-width="12"
                                stroke-dasharray="{{ $baik_length }} {{ $circumference }}"
                                stroke-dashoffset="{{ -$offset }}" class="transition-all duration-500" />
                            @php $offset += $baik_length; @endphp
                        @endif
                        @if ($total > 0 && $kondisiAsetData['KB'] > 0)
                            @php
                                $kb_persen = $kondisiAsetData['KB'] / $total;
                                $kb_length = $kb_persen * $circumference;
                            @endphp
                            <circle cx="60" cy="60" r="54" fill="none" stroke="#f59e0b"
                                stroke-width="12" stroke-dasharray="{{ $kb_length }} {{ $circumference }}"
                                stroke-dashoffset="{{ -$offset }}" class="transition-all duration-500" />
                            @php $offset += $kb_length; @endphp
                        @endif
                        @if ($total > 0 && $kondisiAsetData['RB'] > 0)
                            @php
                                $rb_persen = $kondisiAsetData['RB'] / $total;
                                $rb_length = $rb_persen * $circumference;
                            @endphp
                            <circle cx="60" cy="60" r="54" fill="none" stroke="#ef4444"
                                stroke-width="12" stroke-dasharray="{{ $rb_length }} {{ $circumference }}"
                                stroke-dashoffset="{{ -$offset }}" class="transition-all duration-500" />
                        @endif
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-800">
                                {{ $total > 0 ? number_format($persentaseKondisi['B'], 0) : 0 }}%</p>
                            <p class="text-xs text-gray-500">Kondisi Baik</p>
                        </div>
                    </div>
                </div>

                {{-- Legend --}}
                <div class="mt-4 space-y-2 w-full">
                    <div
                        class="flex items-center justify-between px-3 py-2 bg-green-50 rounded-lg border border-green-100">
                        <div class="flex items-center space-x-2">
                            <div class="w-2.5 h-2.5 bg-green-500 rounded-full"></div>
                            <span class="text-xs font-medium text-gray-700">Baik (B)</span>
                        </div>
                        <div class="text-right">
                            <span
                                class="text-sm font-bold text-gray-800">{{ number_format($kondisiAsetData['B']) }}</span>
                            <span class="text-xs text-gray-500 ml-1">({{ $persentaseKondisi['B'] }}%)</span>
                        </div>
                    </div>
                    <div
                        class="flex items-center justify-between px-3 py-2 bg-amber-50 rounded-lg border border-amber-100">
                        <div class="flex items-center space-x-2">
                            <div class="w-2.5 h-2.5 bg-amber-500 rounded-full"></div>
                            <span class="text-xs font-medium text-gray-700">Kurang Baik (KB)</span>
                        </div>
                        <div class="text-right">
                            <span
                                class="text-sm font-bold text-gray-800">{{ number_format($kondisiAsetData['KB']) }}</span>
                            <span class="text-xs text-gray-500 ml-1">({{ $persentaseKondisi['KB'] }}%)</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between px-3 py-2 bg-red-50 rounded-lg border border-red-100">
                        <div class="flex items-center space-x-2">
                            <div class="w-2.5 h-2.5 bg-red-500 rounded-full"></div>
                            <span class="text-xs font-medium text-gray-700">Rusak Berat (RB)</span>
                        </div>
                        <div class="text-right">
                            <span
                                class="text-sm font-bold text-gray-800">{{ number_format($kondisiAsetData['RB']) }}</span>
                            <span class="text-xs text-gray-500 ml-1">({{ $persentaseKondisi['RB'] }}%)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================
            4. MUTASI TERBARU & WARNING
        ======================================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Mutasi Terbaru (2 kolom) --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-gray-800">Mutasi Terbaru</h3>
                    <a href="{{ route('mutasi.index') }}"
                        class="text-xs text-blue-600 hover:text-blue-700 font-medium inline-flex items-center gap-1">
                        Lihat Semua
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                </div>

                @if ($mutasiTerbaru->count() > 0)
                    <div class="space-y-2.5">
                        @foreach ($mutasiTerbaru as $mutasi)
                            <div
                                class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 border border-gray-100">
                                <div class="flex-shrink-0 mt-0.5">
                                    <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-gray-900 truncate">
                                                {{ $mutasi->aset->nama_jenis_barang }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $mutasi->aset->kode_barang }} -
                                                Reg: {{ $mutasi->aset->register }}</p>
                                        </div>
                                        <span
                                            class="text-xs text-gray-400 ml-3">{{ $mutasi->tanggal_mutasi->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="mt-1.5 flex items-center text-xs">
                                        <span
                                            class="px-2 py-0.5 bg-red-100 text-red-700 rounded">{{ $mutasi->ruangan_asal }}</span>
                                        <svg class="w-3.5 h-3.5 mx-1.5 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                        <span
                                            class="px-2 py-0.5 bg-green-100 text-green-700 rounded">{{ $mutasi->ruangan_tujuan }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                            </path>
                        </svg>
                        <p class="text-gray-500 mt-3 text-sm">Belum ada mutasi aset</p>
                    </div>
                @endif
            </div>

            {{-- Warning & Alert (1 kolom) --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <h3 class="text-base font-bold text-gray-800 mb-1">Peringatan Aset</h3>
                <p class="text-xs text-gray-500 mb-4">Monitoring kondisi aset yang memerlukan perhatian</p>

                <div class="space-y-2.5">
                    <div class="p-3 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-red-800">Rusak Berat</span>
                            <span class="text-base font-bold text-red-700">{{ $asetRusakBerat }}</span>
                        </div>
                    </div>

                    <div class="p-3 bg-yellow-50 border-l-4 border-yellow-500 rounded-r-lg">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-yellow-800">Tanpa Lokasi</span>
                            <span class="text-base font-bold text-yellow-700">{{ $asetTanpaLokasi }}</span>
                        </div>
                    </div>

                    <div class="p-3 bg-orange-50 border-l-4 border-orange-500 rounded-r-lg">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-orange-800">Tanpa Ruangan</span>
                            <span class="text-base font-bold text-orange-700">{{ $asetTanpaRuangan }}</span>
                        </div>
                    </div>

                    <div class="p-3 bg-blue-50 border-l-4 border-blue-500 rounded-r-lg">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-medium text-blue-800">Belum Dimutasi</span>
                            <span class="text-base font-bold text-blue-700">{{ $asetBelumDimutasi }}</span>
                        </div>
                        <p class="text-xs text-gray-600">Aset punya ruangan tapi belum dimutasi</p>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('asets.index') }}"
                        class="block w-full text-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-sm">
                        Kelola Aset
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.shadow-sm, .shadow-md');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'all 0.5s ease-out';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 50);
                }, index * 100);
            });
        });
    </script>
@endpush
