@extends('layouts.app')

@section('title', 'Daftar Aset Tetap & Lainnya')

@section('content')
    <div class="container mx-auto px-4 py-6">
        {{-- Header Card --}}
        <div class="mb-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    {{-- Left : Icon + Breadcrumb --}}
                    <div class="flex items-center gap-4">
                        {{-- Breadcrumb --}}
                        <div class="flex flex-col">
                            <x-breadcrumbs.aset current="{{ $breadcrumb ?? null }}" />
                        </div>
                    </div>

                    {{-- Right : Actions --}}
                    <div class="flex flex-wrap items-center gap-2">
                        <form action="{{ route('asets.export') }}" method="GET" class="inline-block">
                            <input type="hidden" name="search" id="export_search" value="{{ request('search') }}">
                            <input type="hidden" name="tahun_perolehan" id="export_tahun_perolehan"
                                value="{{ request('tahun_perolehan') }}">
                            <input type="hidden" name="keadaan_barang" id="export_keadaan_barang"
                                value="{{ request('keadaan_barang') }}">
                            <input type="hidden" name="ruangan" id="export_ruangan" value="{{ request('ruangan') }}">
                            <button type="submit"
                                class="inline-flex items-center h-9 px-4
           bg-white border border-green-600 text-green-700
           rounded-lg text-sm font-medium
           hover:bg-green-600 hover:text-white
           transition-all duration-300 shadow-sm"
                                id="exportFormBtn">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" fill="none">
                                    <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z">
                                    </path>
                                </svg>
                                Export Excel
                            </button>
                        </form>
                        <form id="batch-label-form" action="{{ route('asets.downloadBatchLabel') }}" method="POST"
                            class="inline-block">
                            @csrf
                            <button type="submit" id="batch-label-btn" disabled
                                class="inline-flex items-center h-9 px-4
       bg-white border border-sky-600 text-sky-700
       rounded-lg text-sm font-medium
       hover:bg-sky-600 hover:text-white
       disabled:opacity-50 disabled:cursor-not-allowed
       transition-all duration-300 shadow-sm"
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" fill="none">
                                <path d="M7 7h10a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2z" />
                                <path d="M12 7v6m0 0l2-2m-2 2l-2-2" />
                                <path d="M7 17h10" />
                                </svg>
                                <span id="batch-label-text">Download Label Terpilih</span>
                            </button>
                        </form>
                        <a href="{{ route('asets.create') }}"
                            class="inline-flex items-center h-9 px-4
                           bg-gradient-to-r from-blue-600 to-blue-700
                           text-white rounded-lg text-sm font-medium
                           hover:from-blue-700 hover:to-blue-800
                           transition-all duration-300 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" fill="none">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Tambah Aset Tetap & Lainnya
                        </a>
                    </div>

                </div>
            </div>
        </div>

        {{-- Success Alert --}}
        {{-- @if (session('success'))
            <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-lg shadow-md p-4 flex items-start animate-fade-in"
                role="alert">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" fill="none">
                        <path d="M5 12l5 5l10 -10"></path>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h4 class="text-sm font-bold text-green-800">Berhasil!</h4>
                    <p class="text-sm text-green-700 mt-1">{{ session('success') }}</p>
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
        @endif --}}

        {{-- Error Alert --}}
        {{-- @if (session('error'))
            <div class="mb-6 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 rounded-lg shadow-md p-4 flex items-start animate-fade-in"
                role="alert">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" fill="none">
                        <circle cx="12" cy="12" r="9"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h4 class="text-sm font-bold text-red-800">Error!</h4>
                    <p class="text-sm text-red-700 mt-1">{{ session('error') }}</p>
                </div>
                <button type="button" class="ml-3 flex-shrink-0 text-red-600 hover:text-red-800"
                    onclick="this.parentElement.remove()">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        @endif --}}

        {{-- Filter Card --}}
        <div class="bg-white rounded-xl shadow-sm mb-4 border border-gray-200">
            <div class="p-4">
                <form method="GET" action="{{ route('asets.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-7 gap-3 items-end">

                        {{-- Pencarian --}}
                        <div class="lg:col-span-1">
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                Pencarian
                            </label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama, kode, register"
                                class="w-full h-9 rounded-lg border-gray-300 text-sm px-3
                        focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Tahun Perolehan --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                Tahun Perolehan
                            </label>
                            <select name="tahun_perolehan"
                                class="w-full h-9 rounded-lg border-gray-300 text-sm px-2
                        focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua</option>
                                @for ($year = date('Y'); $year >= 2000; $year--)
                                    <option value="{{ $year }}" @selected(request('tahun_perolehan') == $year)>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        {{-- Tahun Dari --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                Tahun Dari
                            </label>
                            <select name="tahun_dari"
                                class="w-full h-9 rounded-lg border-gray-300 text-sm px-2
                        focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Dari</option>
                                @for ($year = 1990; $year <= date('Y'); $year++)
                                    <option value="{{ $year }}" @selected(request('tahun_dari') == $year)>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        {{-- Tahun Sampai --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                Tahun Sampai
                            </label>
                            <select name="tahun_sampai"
                                class="w-full h-9 rounded-lg border-gray-300 text-sm px-2
                        focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Sampai</option>
                                @for ($year = 1990; $year <= date('Y'); $year++)
                                    <option value="{{ $year }}" @selected(request('tahun_sampai') == $year)>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        {{-- Keadaan Barang --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                Keadaan Barang
                            </label>
                            <select name="keadaan_barang"
                                class="w-full h-9 rounded-lg border-gray-300 text-sm px-2
                        focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua</option>
                                <option value="B" @selected(request('keadaan_barang') == 'B')>Baik</option>
                                <option value="KB" @selected(request('keadaan_barang') == 'KB')>Kurang Baik</option>
                                <option value="RB" @selected(request('keadaan_barang') == 'RB')>Rusak Berat</option>
                            </select>
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

                                @foreach (config('ruangan') as $ruangan)
                                    <option value="{{ $ruangan }}"
                                        {{ request('ruangan') === $ruangan ? 'selected' : '' }}>
                                        {{ $ruangan }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        {{-- ACTION (kanan sejajar keadaan barang) --}}
                        <div class="flex gap-2">
                            <button type="submit"
                                class="h-9 px-7 rounded-lg bg-blue-600 text-white text-sm
                        hover:bg-blue-700 transition">
                                Filter
                            </button>

                            <a href="{{ route('asets.index') }}"
                                class="h-9 px-6 rounded-lg border text-sm text-gray-600
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
                            <path d="M9 11l3 3l8 -8"></path>
                            <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12c0 -1.1 .9 -2 2 -2h9"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-blue-800">Daftar Seluruh Aset</h3>
                        <p class="text-sm text-blue-600">Total {{ $asets->total() }} item terdaftar</p>
                    </div>
                </div>
            </div>

            {{-- Entries Per Page (DI ATAS TABEL) --}}
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-6 py-3
            border-b border-gray-200 bg-gray-50">

                {{-- LEFT : Show Entries --}}
                <form method="GET" action="{{ route('asets.index') }}" class="flex items-center gap-2">

                    {{-- Pertahankan filter lain --}}
                    @foreach (request()->except(['per_page', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <span class="text-sm text-gray-600">Tampilkan</span>

                    <select name="per_page" onchange="this.form.submit()"
                        class="h-8 min-w-[4rem] px-3
           rounded-md border border-gray-300
           text-sm text-gray-700
           focus:ring-blue-500 focus:border-blue-500
           bg-white">

                        @foreach ([10, 20, 30, 50, 100] as $size)
                            <option value="{{ $size }}" @selected(request('per_page', 20) == $size)>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>

                    <span class="text-sm text-gray-600">data</span>
                </form>

                {{-- RIGHT : Info Result --}}
                <div class="text-sm text-gray-600 mt-2 sm:mt-0">
                    Menampilkan
                    <span class="font-semibold">{{ $asets->firstItem() ?? 0 }}</span>
                    –
                    <span class="font-semibold">{{ $asets->lastItem() ?? 0 }}</span>
                    dari
                    <span class="font-semibold">{{ $asets->total() }}</span>
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
                            <th class="px-4 py-3 text-center">
                                <input type="checkbox" id="select-all"
                                    class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-4 py-3 text-center font-semibold uppercase tracking-wider whitespace-nowrap">No
                            </th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider whitespace-nowrap">Nama
                                Bidang Barang</th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider whitespace-nowrap">Kode
                                Barang</th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider whitespace-nowrap">
                                Register</th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider whitespace-nowrap">Nama
                                Jenis Barang</th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider whitespace-nowrap">Merk /
                                Type</th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider whitespace-nowrap">No.
                                Sertifikat</th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider whitespace-nowrap">No.
                                Plat Kendaraan</th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider whitespace-nowrap">No.
                                Pabrik</th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider whitespace-nowrap">No.
                                Casis</th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider whitespace-nowrap">Bahan
                            </th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider whitespace-nowrap">Asal
                                Perolehan</th>
                            <th class="px-4 py-3 text-center font-semibold uppercase tracking-wider whitespace-nowrap">
                                Tahun Perolehan</th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider whitespace-nowrap">Ukuran
                                Barang / Konstruksi</th>
                            <th class="px-4 py-3 text-center font-semibold uppercase tracking-wider whitespace-nowrap">
                                Satuan</th>
                            <th class="px-4 py-3 text-center font-semibold uppercase tracking-wider whitespace-nowrap">
                                Keadaan Barang</th>
                            <th class="px-4 py-3 text-center font-semibold uppercase tracking-wider whitespace-nowrap">
                                Jumlah Barang</th>
                            <th class="px-4 py-3 text-right font-semibold uppercase tracking-wider whitespace-nowrap">Harga
                                Satuan</th>
                            <th class="px-4 py-3 text-right font-semibold uppercase tracking-wider whitespace-nowrap">Total
                                Harga</th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider whitespace-nowrap">Lokasi
                                Barang</th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider whitespace-nowrap">
                                Ruangan</th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wider whitespace-nowrap">
                                Keterangan</th>
                            <th class="px-4 py-3 text-center font-semibold uppercase tracking-wider whitespace-nowrap">
                                Bukti Barang</th>
                            <th class="px-4 py-3 text-center font-semibold uppercase tracking-wider whitespace-nowrap">
                                Bukti Berita</th>
                            <th class="px-4 py-3 text-center font-semibold uppercase tracking-wider whitespace-nowrap">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($asets as $index => $aset)
                            <tr
                                class="hover:bg-blue-50 transition-colors duration-200 {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                <td class="px-4 py-3 text-center">
                                    <input type="checkbox"
                                        class="asset-checkbox w-4 h-4 rounded border-gray-300 text-blue-600"
                                        name="asset_ids[]" value="{{ $aset->id }}" form="batch-label-form">
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-700 rounded-lg font-semibold text-xs">
                                        {{ ($asets->currentPage() - 1) * $asets->perPage() + $index + 1 }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $aset->nama_bidang_barang }}</td>
                                <td class="px-4 py-3 text-gray-700 font-mono text-xs whitespace-nowrap">
                                    {{ $aset->kode_barang }}</td>
                                <td class="px-4 py-3 text-gray-700 font-mono text-xs whitespace-nowrap">
                                    {{ $aset->register }}</td>
                                <td class="px-4 py-3 text-gray-700 whitespace-nowrap">{{ $aset->nama_jenis_barang }}</td>
                                <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $aset->merk_type ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600 font-mono text-xs whitespace-nowrap">
                                    {{ $aset->no_sertifikat ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600 font-mono text-xs whitespace-nowrap">
                                    {{ $aset->no_plat_kendaraan ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600 font-mono text-xs whitespace-nowrap">
                                    {{ $aset->no_pabrik ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600 font-mono text-xs whitespace-nowrap">
                                    {{ $aset->no_casis ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $aset->bahan ?? '-' }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $aset->asal_perolehan }}</td>
                                <td class="px-4 py-3 text-center text-gray-700 font-mono whitespace-nowrap">
                                    {{ $aset->tahun_perolehan }}</td>
                                <td class="px-4 py-3 text-gray-600 text-xs whitespace-nowrap">
                                    {{ $aset->ukuran_barang_konstruksi ?? '-' }}</td>
                                <td class="px-4 py-3 text-center text-gray-700 font-mono whitespace-nowrap">
                                    {{ $aset->satuan }}</td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                        {{ $aset->keadaan_barang === 'B'
                                            ? 'bg-green-100 text-green-800'
                                            : ($aset->keadaan_barang === 'KB'
                                                ? 'bg-yellow-100 text-yellow-800'
                                                : 'bg-red-100 text-red-800') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" fill="none">
                                            <circle cx="12" cy="12" r="9"></circle>
                                            @if ($aset->keadaan_barang === 'B')
                                                <path d="m9 12 2 2 4-4"></path>
                                            @elseif($aset->keadaan_barang === 'KB')
                                                <path d="M12 9v4"></path>
                                                <path d="m12 16 .01 0"></path>
                                            @else
                                                <path d="m15 9-6 6"></path>
                                                <path d="m9 9 6 6"></path>
                                            @endif
                                        </svg>
                                        {{ $aset->keadaan_barang }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center text-gray-700 font-mono whitespace-nowrap">
                                    {{ $aset->jumlah_barang }}</td>
                                <td
                                    class="px-4 py-3 text-right font-medium text-gray-900 font-mono text-xs whitespace-nowrap">
                                    {{ $aset->formatted_harga }}</td>
                                <td
                                    class="px-4 py-3 text-right font-bold text-green-600 font-mono text-xs whitespace-nowrap">
                                    {{ $aset->formatted_total_harga }}</td>
                                <td class="px-4 py-3 text-gray-700 whitespace-nowrap">{{ $aset->lokasi_barang ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-gray-700 whitespace-nowrap">{{ $aset->ruangan ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600 text-xs">
                                    @if ($aset->keterangan)
                                        {{ Str::limit($aset->keterangan, 100) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    @if ($aset->bukti_barang_url)
                                        <a href="{{ $aset->bukti_barang_url }}" target="_blank"
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                fill="none">
                                                <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                                <path
                                                    d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z">
                                                </path>
                                            </svg>
                                            Lihat
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    @if ($aset->bukti_berita_url)
                                        <a href="{{ $aset->bukti_berita_url }}" target="_blank"
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                fill="none">
                                                <path
                                                    d="M16 6h3a1 1 0 0 1 1 1v11a2 2 0 0 1 -4 0v-13a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1v12a3 3 0 0 0 3 3h11">
                                                </path>
                                                <line x1="8" y1="8" x2="12" y2="8">
                                                </line>
                                                <line x1="8" y1="12" x2="12" y2="12">
                                                </line>
                                            </svg>
                                            Lihat
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <div class="inline-flex gap-1">
                                        <a href="{{ route('asets.show', $aset->id) }}"
                                            class="p-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200"
                                            title="Lihat Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" fill="none">
                                                <circle cx="12" cy="12" r="2"></circle>
                                                <path
                                                    d="M22 12c-2.667 4 -6 6 -10 6s-7.333 -2 -10 -6c2.667 -4 6 -6 10 -6s7.333 2 10 6">
                                                </path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('asets.edit', $aset->id) }}"
                                            class="p-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors duration-200"
                                            title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" fill="none">
                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                                <path
                                                    d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z">
                                                </path>
                                                <path d="M16 5l3 3"></path>
                                            </svg>
                                        </a>
                                        <button
                                            class="p-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors duration-200"
                                            onclick="confirmDelete('{{ route('asets.destroy', $aset->id) }}')"
                                            title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" fill="none">
                                                <line x1="4" y1="7" x2="20" y2="7">
                                                </line>
                                                <line x1="10" y1="11" x2="10" y2="17">
                                                </line>
                                                <line x1="14" y1="11" x2="14" y2="17">
                                                </line>
                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                            </svg>
                                        </button>
                                        <a href="{{ route('asets.downloadPdf', $aset->id) }}"
                                            class="p-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors duration-200"
                                            title="Download PDF">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" fill="none">
                                                <path d="M12 3v12m0 0l-4-4m4 4l4-4" />
                                                <rect x="4" y="17" width="16" height="4" rx="2" />
                                            </svg>
                                        </a>
                                        <!-- Download Label (NEW) -->
                                        <a href="{{ route('asets.downloadLabel', $aset->id) }}"
                                            class="p-2 bg-teal-100 text-teal-700 rounded-lg hover:bg-teal-200 transition-colors duration-200"
                                            title="Download Label">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" fill="none">
                                                <path
                                                    d="M7 7h10a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2z" />
                                                <path d="M12 7v6m0 0l2-2m-2 2l-2-2" />
                                                <path d="M7 17h10" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="26" class="px-4 py-16">
                                    <div class="text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-24 h-24 mx-auto text-blue-300 mb-4" viewBox="0 0 24 24"
                                            stroke-width="1" stroke="currentColor" fill="none">
                                            <rect x="4" y="4" width="6" height="6" rx="1"></rect>
                                            <rect x="14" y="4" width="6" height="6" rx="1"></rect>
                                            <rect x="4" y="14" width="6" height="6" rx="1"></rect>
                                            <rect x="14" y="14" width="6" height="6" rx="1"></rect>
                                        </svg>
                                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum ada data aset</h3>
                                        <p class="text-gray-500 mb-6">Mulai dengan menambahkan aset pertama untuk
                                            organisasi Anda</p>
                                        <a href="{{ route('asets.create') }}"
                                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl font-medium">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                fill="none">
                                                <line x1="12" y1="5" x2="12" y2="19">
                                                </line>
                                                <line x1="5" y1="12" x2="19" y2="12">
                                                </line>
                                            </svg>
                                            Tambah Aset Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            @if ($asets->hasPages())
                <div class="bg-gradient-to-r from-blue-100 via-blue-50 to-blue-100 border-t border-blue-200 px-6 py-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center text-sm text-blue-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" fill="none">
                                <path d="M9 11l3 3l8 -8"></path>
                                <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12c0 -1.1 .9 -2 2 -2h9"></path>
                            </svg>
                            <span>
                                Menampilkan <span class="font-bold text-blue-800">{{ $asets->firstItem() }}</span>
                                sampai <span class="font-bold text-blue-800">{{ $asets->lastItem() }}</span>
                                dari <span class="font-bold text-blue-800">{{ $asets->total() }}</span> data
                            </span>
                        </div>
                        <div class="flex justify-center sm:justify-end">
                            {{ $asets->links('vendor.pagination.simple-numbered') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Delete Form --}}
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    {{-- Confirm Modal --}}
    <x-notifications.confirm-modal />
@endsection

@push('page-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Script loaded');

            // ==========================================
            // 1. SYNC SCROLLBAR HORIZONTAL
            // ==========================================
            const topScroll = document.getElementById('scroll-top');
            const tableScroll = document.getElementById('scroll-table');
            const topInner = document.getElementById('scroll-top-inner');

            function syncWidth() {
                if (topInner && tableScroll) {
                    topInner.style.width = tableScroll.scrollWidth + 'px';
                }
            }

            syncWidth();
            window.addEventListener('resize', syncWidth);

            if (topScroll && tableScroll) {
                topScroll.addEventListener('scroll', () => {
                    tableScroll.scrollLeft = topScroll.scrollLeft;
                });

                tableScroll.addEventListener('scroll', () => {
                    topScroll.scrollLeft = tableScroll.scrollLeft;
                });
            }

            // ==========================================
            // 2. EXPORT EXCEL FORM SYNC
            // ==========================================
            function updateExportForm() {
                const search = document.querySelector('input[name="search"]');
                const tahunPerolehan = document.querySelector('select[name="tahun_perolehan"]');
                const keadaanBarang = document.querySelector('select[name="keadaan_barang"]');
                const ruangan = document.querySelector('select[name="ruangan"]');

                if (document.getElementById('export_search')) {
                    document.getElementById('export_search').value = search ? search.value : '';
                }
                if (document.getElementById('export_tahun_perolehan')) {
                    document.getElementById('export_tahun_perolehan').value = tahunPerolehan ? tahunPerolehan
                        .value : '';
                }
                if (document.getElementById('export_keadaan_barang')) {
                    document.getElementById('export_keadaan_barang').value = keadaanBarang ? keadaanBarang.value :
                        '';
                }
                if (document.getElementById('export_ruangan')) {
                    document.getElementById('export_ruangan').value = ruangan ? ruangan.value : '';
                }

                const hasFilters = (search && search.value) ||
                    (tahunPerolehan && tahunPerolehan.value) ||
                    (keadaanBarang && keadaanBarang.value) ||
                    (ruangan && ruangan.value);

                const btn = document.getElementById('exportFormBtn');
                if (btn) {
                    if (hasFilters) {
                        btn.classList.remove('border-blue-600', 'text-blue-600', 'hover:bg-blue-600');
                        btn.classList.add('border-green-600', 'text-green-600', 'hover:bg-green-600');
                        const span = btn.querySelector('span');
                        if (span) span.textContent = 'Export Excel (Filtered)';
                    } else {
                        btn.classList.remove('border-green-600', 'text-green-600', 'hover:bg-green-600');
                        btn.classList.add('border-blue-600', 'text-blue-600', 'hover:bg-blue-600');
                        const span = btn.querySelector('span');
                        if (span) span.textContent = 'Export Excel';
                    }
                }
            }

            // Attach listeners untuk filter
            const filterInputs = document.querySelectorAll(
                'input[name="search"], select[name="tahun_perolehan"], select[name="keadaan_barang"], select[name="ruangan"]'
            );
            filterInputs.forEach(input => {
                input.addEventListener('change', updateExportForm);
                input.addEventListener('input', updateExportForm);
            });

            // ==========================================
            // 3. BATCH LABEL DOWNLOAD (FITUR UTAMA)
            // ==========================================
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.asset-checkbox');
            const btn = document.getElementById('batch-label-btn');
            const text = document.getElementById('batch-label-text');
            const form = document.getElementById('batch-label-form');

            console.log('📋 Total checkboxes found:', checkboxes.length);

            // ✅ Fungsi update button
            function updateBatchButton() {
                const checkedCount = document.querySelectorAll('.asset-checkbox:checked').length;

                console.log('✅ Checked count:', checkedCount);

                if (btn && text) {
                    btn.disabled = checkedCount === 0;
                    text.textContent = checkedCount > 0 ?
                        `Download Label Terpilih (${checkedCount})` :
                        'Download Label Terpilih';
                }
            }

            // ✅ Select All functionality
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    console.log('🔄 Select All:', this.checked);
                    checkboxes.forEach(cb => {
                        cb.checked = this.checked;
                    });
                    updateBatchButton();
                });
            }

            // ✅ Individual checkbox change
            checkboxes.forEach((cb, index) => {
                cb.addEventListener('change', function() {
                    console.log(`📦 Checkbox ${index + 1} changed:`, this.checked);

                    // Update select-all state
                    if (selectAll) {
                        const allChecked = Array.from(checkboxes).every(c => c.checked);
                        selectAll.checked = allChecked;
                    }

                    updateBatchButton();
                });
            });

            // ✅ Form submit handler
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const checked = document.querySelectorAll('.asset-checkbox:checked');
                    console.log('📤 Submitting with', checked.length, 'checked items');

                    if (checked.length === 0) {
                        alert('⚠️ Pilih minimal satu aset untuk diunduh labelnya.');
                        return;
                    }

                    if (!confirm(`📦 Download label untuk ${checked.length} aset?`)) {
                        return;
                    }

                    // ✅ Tidak perlu manipulasi form karena checkbox sudah terhubung via form="..."
                    btn.disabled = true;
                    text.textContent = 'Membuat label...';

                    // Submit form
                    this.submit();
                });
            }

            // Initial update
            updateBatchButton();

            // ==========================================
            // 4. AUTO DISMISS ALERTS
            // ==========================================
            setTimeout(function() {
                const alerts = document.querySelectorAll('.animate-fade-in');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);

            // ==========================================
            // 5. YEAR FILTER CONFLICT HANDLING
            // ==========================================
            const tahunPerolehan = document.querySelector('select[name="tahun_perolehan"]');
            const tahunDari = document.querySelector('select[name="tahun_dari"]');
            const tahunSampai = document.querySelector('select[name="tahun_sampai"]');

            function clearConflictingFilters(currentFilter) {
                if (currentFilter === 'single') {
                    if (tahunDari) tahunDari.value = '';
                    if (tahunSampai) tahunSampai.value = '';
                } else if (currentFilter === 'range') {
                    if (tahunPerolehan) tahunPerolehan.value = '';
                }
                updateExportForm();
            }

            if (tahunPerolehan) {
                tahunPerolehan.addEventListener('change', function() {
                    if (this.value !== '') clearConflictingFilters('single');
                });
            }

            if (tahunDari) {
                tahunDari.addEventListener('change', function() {
                    if (this.value !== '') clearConflictingFilters('range');
                });
            }

            if (tahunSampai) {
                tahunSampai.addEventListener('change', function() {
                    if (this.value !== '') clearConflictingFilters('range');

                    // Validate range
                    const dari = parseInt(tahunDari ? tahunDari.value : 0);
                    const sampai = parseInt(this.value);

                    if (dari && sampai && dari > sampai) {
                        alert('Tahun dari tidak boleh lebih besar dari tahun sampai!');
                        this.value = '';
                        updateExportForm();
                    }
                });
            }
        });

        // ==========================================
        // 6. DELETE CONFIRMATION (GLOBAL)
        // ==========================================
        function confirmDelete(url) {
            // Dapatkan Alpine component
            const modal = Alpine.$data(document.querySelector('[x-data*="confirmModal"]'));

            modal.show({
                title: 'Hapus Aset',
                message: 'Apakah Anda yakin ingin menghapus aset ini? Tindakan ini tidak dapat dibatalkan.',
                confirmText: 'Ya, Hapus',
                cancelText: 'Batal',
                type: 'danger',
                onConfirm: () => {
                    const form = document.getElementById('delete-form');
                    if (form) {
                        form.action = url;
                        form.submit();
                    }
                }
            });
        }
    </script>
@endpush

@push('page-styles')
    <style>
        .custom-scrollbar {
            scrollbar-color: #3b82f6 #f1f5f9;
            /* thumb | track */
            scrollbar-width: auto;
            /* default */
        }

        /* Firefox */
        .custom-scrollbar {
            scrollbar-color: #3b82f6 #f1f5f9;
        }

        .scrollbar-hidden {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        #scroll-top {
            padding-right: 12px;
        }

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

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
@endpush
