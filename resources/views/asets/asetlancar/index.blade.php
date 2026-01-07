@extends('layouts.app')
@section('title', 'Daftar Aset Lancar')
@section('content')
    <div class="container mx-auto px-4 py-6">
        {{-- Header Card --}}
        <div class="mb-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    {{-- Left : Breadcrumb --}}
                    <div class="flex items-center gap-4">
                        <div class="flex flex-col">
                            <x-breadcrumbs.asetlancar />
                        </div>
                    </div>

                    {{-- Right : Actions --}}
                    <div class="flex flex-wrap items-center gap-2">

                        {{-- Export --}}
                        <form action="{{ route('aset-lancars.export') }}" method="GET" class="inline-block">
                            <input type="hidden" name="search" id="export_search" value="{{ request('search') }}">
                            <input type="hidden" name="rekening_uraian_id" id="export_rekening_uraian_id"
                                value="{{ request('rekening_uraian_id') }}">
                            <input type="hidden" name="nama_kegiatan" id="export_nama_kegiatan"
                                value="{{ request('nama_kegiatan') }}">
                            <input type="hidden" name="uraian_jenis_barang" id="export_uraian_jenis_barang"
                                value="{{ request('uraian_jenis_barang') }}">
                            <input type="hidden" name="date_from" id="export_date_from" value="{{ request('date_from') }}">
                            <input type="hidden" name="date_to" id="export_date_to" value="{{ request('date_to') }}">
                            <input type="hidden" name="saldo_awal_min" id="export_saldo_awal_min"
                                value="{{ request('saldo_awal_min') }}">
                            <input type="hidden" name="saldo_awal_max" id="export_saldo_awal_max"
                                value="{{ request('saldo_awal_max') }}">

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

                        {{-- Create --}}
                        <a href="{{ route('aset-lancars.create') }}"
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
                            Tambah Aset Lancar
                        </a>

                    </div>
                </div>
            </div>
        </div>


        {{-- Filter Card --}}
        <div class="bg-white rounded-xl shadow-sm mb-4 border border-gray-200">
            <div class="p-4">
                <form method="GET" action="{{ route('aset-lancars.index') }}">
                    <div class="flex flex-wrap items-end gap-3">

                        {{-- Pencarian --}}
                        <div class="w-48">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Pencarian</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari data..."
                                class="w-full h-9 rounded-lg border-gray-300 text-sm px-3 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Rekening Uraian --}}
                        <div class="w-56">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Rekening Uraian</label>
                            <select name="rekening_uraian_id"
                                class="w-full h-9 rounded-lg border-gray-300 text-sm px-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Rekening</option>
                                @foreach ($rekeningUraians as $rekening)
                                    <option value="{{ $rekening->id }}"
                                        {{ request('rekening_uraian_id') == $rekening->id ? 'selected' : '' }}>
                                        {{ $rekening->kode_rekening }} - {{ $rekening->uraian }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Nama Kegiatan --}}
                        <div class="w-44">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Nama Kegiatan</label>
                            <input type="text" name="nama_kegiatan" value="{{ request('nama_kegiatan') }}"
                                placeholder="Kegiatan"
                                class="w-full h-9 rounded-lg border-gray-300 text-sm px-3 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Jenis Barang --}}
                        <div class="w-44">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Barang</label>
                            <input type="text" name="uraian_jenis_barang" value="{{ request('uraian_jenis_barang') }}"
                                placeholder="Jenis barang"
                                class="w-full h-9 rounded-lg border-gray-300 text-sm px-3 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Tanggal Dari --}}
                        <div class="w-36">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Dari</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                class="w-full h-9 rounded-lg border-gray-300 text-sm px-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Tanggal Sampai --}}
                        <div class="w-36">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Sampai</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                class="w-full h-9 rounded-lg border-gray-300 text-sm px-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-2 ml-auto">
                            <button type="submit"
                                class="h-9 px-6 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700 transition font-medium">
                                Filter
                            </button>
                            <a href="{{ route('aset-lancars.index') }}"
                                class="h-9 px-7 rounded-lg border text-sm text-gray-600 hover:bg-gray-100 transition flex items-center font-medium">
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
                            <polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3"></polyline>
                            <line x1="12" y1="12" x2="20" y2="7.5"></line>
                            <line x1="12" y1="12" x2="12" y2="21"></line>
                            <line x1="12" y1="12" x2="4" y2="7.5"></line>
                            <line x1="16" y1="5.25" x2="8" y2="9.75"></line>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-blue-800">Daftar Aset Lancar</h3>
                        <p class="text-sm text-blue-600">Total {{ $asetLancars->total() }} item terdaftar</p>
                    </div>
                </div>
            </div>

            {{-- Entries Per Page (DI ATAS KOLOM TABEL) --}}
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between
            px-6 py-3 border-b border-gray-200 bg-gray-50">

                <form method="GET" action="{{ route('aset-lancars.index') }}" class="flex items-center gap-2">

                    {{-- Pertahankan semua filter --}}
                    @foreach (request()->except(['per_page', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <span class="text-sm text-gray-600">Tampilkan</span>

                    <select name="per_page" onchange="this.form.submit()"
                        class="h-8 min-w-[4rem] px-3
                   rounded-md border border-gray-300
                   text-sm text-gray-700 bg-white
                   focus:ring-blue-500 focus:border-blue-500">
                        @foreach ([10, 20, 30, 50, 100] as $size)
                            <option value="{{ $size }}" @selected(request('per_page', 20) == $size)>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>

                    <span class="text-sm text-gray-600">data</span>
                </form>

                <div class="text-sm text-gray-600 mt-2 sm:mt-0">
                    Menampilkan
                    <span class="font-semibold">{{ $asetLancars->firstItem() ?? 0 }}</span>
                    –
                    <span class="font-semibold">{{ $asetLancars->lastItem() ?? 0 }}</span>
                    dari
                    <span class="font-semibold">{{ $asetLancars->total() }}</span>
                    data
                </div>
            </div>

            {{-- Scroll Atas --}}
            <div id="scroll-top" class="overflow-x-auto h-4 mb-2 custom-scrollbar">
                <div id="scroll-top-inner" class="h-4"></div>
            </div>

            {{-- Table --}}
            @if ($asetLancars->count() > 0)
                <div id="scroll-table" class="overflow-x-auto custom-scrollbar scrollbar-hidden">
                    <table class="w-full min-w-max text-sm">
                        <thead class="bg-gradient-to-r from-blue-600 to-blue-700 text-white sticky top-0 z-10">
                            <tr>
                                <th rowspan="2"
                                    class="px-4 py-3 text-center font-semibold uppercase tracking-wider align-middle whitespace-nowrap">
                                    No</th>
                                <th rowspan="2"
                                    class="px-4 py-3 text-left font-semibold uppercase tracking-wider align-middle whitespace-nowrap">
                                    Uraian Barang</th>
                                <th rowspan="2"
                                    class="px-4 py-3 text-left font-semibold uppercase tracking-wider align-middle whitespace-nowrap">
                                    Nama Kegiatan</th>
                                <th rowspan="2"
                                    class="px-4 py-3 text-left font-semibold uppercase tracking-wider align-middle whitespace-nowrap">
                                    Uraian Kegiatan</th>
                                <th rowspan="2"
                                    class="px-4 py-3 text-left font-semibold uppercase tracking-wider align-middle whitespace-nowrap">
                                    Jenis Barang</th>
                                <th colspan="3"
                                    class="px-4 py-3 text-center font-semibold uppercase tracking-wider border-l border-blue-500">
                                    Saldo Awal</th>
                                <th colspan="5"
                                    class="px-4 py-3 text-center font-semibold uppercase tracking-wider border-l border-blue-500">
                                    Mutasi</th>
                                <th colspan="2"
                                    class="px-4 py-3 text-center font-semibold uppercase tracking-wider border-l border-blue-500">
                                    Saldo Akhir</th>
                                <th rowspan="2"
                                    class="px-4 py-3 text-center font-semibold uppercase tracking-wider align-middle whitespace-nowrap">
                                    Aksi</th>
                            </tr>
                            <tr>
                                {{-- Saldo Awal --}}
                                <th class="px-4 py-3 text-center font-semibold uppercase tracking-wider whitespace-nowrap">
                                    Unit</th>
                                <th class="px-4 py-3 text-center font-semibold uppercase tracking-wider whitespace-nowrap">
                                    Harga Satuan</th>
                                <th class="px-4 py-3 text-center font-semibold uppercase tracking-wider whitespace-nowrap">
                                    Nilai Total</th>
                                {{-- Mutasi --}}
                                <th class="px-4 py-3 text-center font-semibold uppercase tracking-wider whitespace-nowrap">
                                    Tambah</th>
                                <th class="px-4 py-3 text-center font-semibold uppercase tracking-wider whitespace-nowrap">
                                    Harga Satuan</th>
                                <th class="px-4 py-3 text-center font-semibold uppercase tracking-wider whitespace-nowrap">
                                    Nilai Total</th>
                                <th class="px-4 py-3 text-center font-semibold uppercase tracking-wider whitespace-nowrap">
                                    (Kurang)</th>
                                <th class="px-4 py-3 text-center font-semibold uppercase tracking-wider whitespace-nowrap">
                                    Nilai Total</th>
                                {{-- Saldo Akhir --}}
                                <th class="px-4 py-3 text-center font-semibold uppercase tracking-wider whitespace-nowrap">
                                    Unit</th>
                                <th class="px-4 py-3 text-center font-semibold uppercase tracking-wider whitespace-nowrap">
                                    Nilai Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($asetLancars as $index => $aset)
                                <tr
                                    class="hover:bg-blue-50 transition-colors duration-200 {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                    <td class="px-4 py-3 text-center">
                                        <span
                                            class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-700 rounded-lg font-semibold text-xs">
                                            {{ ($asetLancars->currentPage() - 1) * $asetLancars->perPage() + $index + 1 }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $aset->rekeningUraian->kode_rekening }} - {{ $aset->rekeningUraian->uraian }}
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $aset->nama_kegiatan }}</td>
                                    <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                                        {{ $aset->uraian_kegiatan ? Str::limit($aset->uraian_kegiatan, 40) : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                                        {{ $aset->uraian_jenis_barang ?? '-' }}</td>

                                    {{-- Saldo Awal --}}
                                    <td class="px-4 py-3 text-center bg-blue-50">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            {{ format_number_id($aset->saldo_awal_unit) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right bg-blue-50 font-mono text-xs text-gray-900">
                                        {{ format_rupiah($aset->saldo_awal_harga_satuan, 0) }}
                                    </td>
                                    <td class="px-4 py-3 text-right bg-blue-50 font-mono text-xs font-bold text-blue-700">
                                        {{ format_rupiah($aset->saldo_awal_total, 0) }}
                                    </td>

                                    {{-- Mutasi --}}
                                    <td class="px-4 py-3 text-center bg-green-50">
                                        @if ($aset->mutasi_tambah_unit > 0)
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                +{{ format_number_id($aset->mutasi_tambah_unit) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right bg-green-50 font-mono text-xs text-green-700">
                                        {{ $aset->mutasi_tambah_harga_satuan > 0 ? format_rupiah($aset->mutasi_tambah_harga_satuan, 0) : '-' }}
                                    </td>
                                    <td
                                        class="px-4 py-3 text-right bg-green-50 font-mono text-xs font-bold text-green-700">
                                        {{ $aset->mutasi_tambah_nilai_total > 0 ? format_rupiah($aset->mutasi_tambah_nilai_total, 0) : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-center bg-green-50">
                                        @if ($aset->mutasi_kurang_unit > 0)
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                -{{ format_number_id($aset->mutasi_kurang_unit) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right bg-green-50 font-mono text-xs font-bold text-red-700">
                                        {{ $aset->mutasi_kurang_nilai_total > 0 ? format_rupiah($aset->mutasi_kurang_nilai_total, 0) : '-' }}
                                    </td>

                                    {{-- Saldo Akhir --}}
                                    <td class="px-4 py-3 text-center bg-yellow-50">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                            {{ format_number_id($aset->saldo_akhir_unit) }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-4 py-3 text-right bg-yellow-50 font-mono text-xs font-bold text-yellow-700">
                                        {{ format_rupiah($aset->saldo_akhir_total, 0) }}
                                    </td>

                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        <div class="inline-flex gap-1">
                                            <a href="{{ route('aset-lancars.show', $aset) }}"
                                                class="p-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200"
                                                title="Lihat Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                    fill="none">
                                                    <circle cx="12" cy="12" r="2"></circle>
                                                    <path
                                                        d="M22 12c-2.667 4 -6 6 -10 6s-7.333 -2 -10 -6c2.667 -4 6 -6 10 -6s7.333 2 10 6">
                                                    </path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('aset-lancars.edit', $aset) }}"
                                                class="p-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors duration-200"
                                                title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                    fill="none">
                                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1">
                                                    </path>
                                                    <path
                                                        d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z">
                                                    </path>
                                                    <path d="M16 5l3 3"></path>
                                                </svg>
                                            </a>
                                            <button
                                                class="p-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors duration-200"
                                                onclick="confirmDelete('{{ route('aset-lancars.destroy', $aset->id) }}')"
                                                title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                    fill="none">
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
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gradient-to-r from-blue-100 via-blue-50 to-blue-100">
                            <tr>
                                <th colspan="7" class="px-4 py-3 text-right font-bold text-blue-800">Total Keseluruhan:
                                </th>
                                <th class="px-4 py-3 text-right font-bold text-blue-800 font-mono text-xs">
                                    {{ format_rupiah($asetLancars->sum('saldo_awal_total'), 0) }}
                                </th>
                                <th colspan="2"></th>
                                <th class="px-4 py-3 text-right font-bold text-green-700 font-mono text-xs">
                                    {{ format_rupiah($asetLancars->sum('mutasi_tambah_nilai_total'), 0) }}
                                </th>
                                <th></th>
                                <th class="px-4 py-3 text-right font-bold text-red-700 font-mono text-xs">
                                    {{ format_rupiah($asetLancars->sum('mutasi_kurang_nilai_total'), 0) }}
                                </th>
                                <th></th>
                                <th class="px-4 py-3 text-right font-bold text-yellow-700 font-mono text-xs">
                                    {{ format_rupiah($asetLancars->sum('saldo_akhir_total'), 0) }}
                                </th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Pagination Footer --}}
                @if ($asetLancars->hasPages())
                    <div class="bg-gradient-to-r from-blue-100 via-blue-50 to-blue-100 border-t border-blue-200 px-6 py-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-center text-sm text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" fill="none">
                                    <polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3"></polyline>
                                    <line x1="12" y1="12" x2="20" y2="7.5"></line>
                                    <line x1="12" y1="12" x2="12" y2="21"></line>
                                    <line x1="12" y1="12" x2="4" y2="7.5"></line>
                                    <line x1="16" y1="5.25" x2="8" y2="9.75"></line>
                                </svg>
                                <span>
                                    Menampilkan <span
                                        class="font-bold text-blue-800">{{ $asetLancars->firstItem() }}</span>
                                    sampai <span class="font-bold text-blue-800">{{ $asetLancars->lastItem() }}</span>
                                    dari <span class="font-bold text-blue-800">{{ $asetLancars->total() }}</span> data
                                </span>
                            </div>
                            <div class="flex justify-center sm:justify-end">
                                {{ $asetLancars->links('vendor.pagination.simple-numbered') }}
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="px-4 py-16">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 mx-auto text-blue-300 mb-4"
                            viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none">
                            <polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3"></polyline>
                            <line x1="12" y1="12" x2="20" y2="7.5"></line>
                            <line x1="12" y1="12" x2="12" y2="21"></line>
                            <line x1="12" y1="12" x2="4" y2="7.5"></line>
                            <line x1="16" y1="5.25" x2="8" y2="9.75"></line>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum ada data aset lancar</h3>
                        <p class="text-gray-500 mb-6">Mulai dengan menambahkan aset lancar pertama untuk organisasi Anda
                        </p>
                        <a href="{{ route('aset-lancars.create') }}"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" fill="none">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Tambah Aset Lancar Pertama
                        </a>
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
            // Sync horizontal scrollbars
            const topScroll = document.getElementById('scroll-top');
            const tableScroll = document.getElementById('scroll-table');
            const topInner = document.getElementById('scroll-top-inner');

            function syncWidth() {
                if (tableScroll && topInner) {
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
        });

        $(document).ready(function() {
            // Update Export Form
            function updateExportForm() {
                $('#export_search').val($('#search').val() || '');
                $('#export_rekening_uraian_id').val($('#rekening_uraian_id').val() || '');
                $('#export_nama_kegiatan').val($('#nama_kegiatan').val() || '');
                $('#export_uraian_jenis_barang').val($('#uraian_jenis_barang').val() || '');
                $('#export_date_from').val($('#date_from').val() || '');
                $('#export_date_to').val($('#date_to').val() || '');
                $('#export_saldo_awal_min').val($('#saldo_awal_min').val() || '');
                $('#export_saldo_awal_max').val($('#saldo_awal_max').val() || '');

                const hasFilters = $('#search').val() || $('#rekening_uraian_id').val() ||
                    $('#nama_kegiatan').val() || $('#uraian_jenis_barang').val() ||
                    $('#date_from').val() || $('#date_to').val() ||
                    $('#saldo_awal_min').val() || $('#saldo_awal_max').val();

                const $btn = $('#exportFormBtn');

                if (hasFilters) {
                    $btn.removeClass('border-blue-600 text-blue-600 hover:bg-blue-600')
                        .addClass('border-green-600 text-green-600 hover:bg-green-600');
                    const text = $btn.text().trim();
                    if (!text.includes('(Filtered)')) {
                        $btn.html($btn.html().replace('Export Excel', 'Export Excel (Filtered)'));
                    }
                } else {
                    $btn.removeClass('border-green-600 text-green-600 hover:bg-green-600')
                        .addClass('border-blue-600 text-blue-600 hover:bg-blue-600');
                    $btn.html($btn.html().replace('Export Excel (Filtered)', 'Export Excel'));
                }
            }

            $('#search, #rekening_uraian_id, #nama_kegiatan, #uraian_jenis_barang, #date_from, #date_to, #saldo_awal_min, #saldo_awal_max')
                .on('change input', updateExportForm);
            updateExportForm();

            // Auto dismiss alerts
            setTimeout(function() {
                $('.animate-fade-in').fadeOut('slow');
            }, 5000);

            // Smooth scroll on pagination
            $('.pagination a').on('click', function() {
                $('html, body').animate({
                    scrollTop: 0
                }, 500);
            });

            // Add loading state to form submissions
            $('form').on('submit', function() {
                const submitBtn = $(this).find('button[type="submit"]');
                if (submitBtn.length && submitBtn.attr('id') === 'exportFormBtn') {
                    const originalHtml = submitBtn.html();
                    submitBtn.prop('disabled', true);
                    submitBtn.html(
                        '<svg class="animate-spin h-5 w-5 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Exporting...'
                    );

                    setTimeout(() => {
                        submitBtn.prop('disabled', false);
                        submitBtn.html(originalHtml);
                    }, 10000);
                }
            });

            // Validate date range
            $('#date_to').on('change', function() {
                const dateFrom = $('#date_from').val();
                const dateTo = $(this).val();

                if (dateFrom && dateTo && dateFrom > dateTo) {
                    alert('Tanggal sampai tidak boleh lebih kecil dari tanggal dari!');
                    $(this).val('');
                }
            });

            // Validate saldo range
            $('#saldo_awal_max').on('change', function() {
                const min = parseFloat($('#saldo_awal_min').val());
                const max = parseFloat($(this).val());

                if (min && max && min > max) {
                    alert('Saldo maksimum tidak boleh lebih kecil dari saldo minimum!');
                    $(this).val('');
                }
            });
        });

        function confirmDelete(url) {
            const modal = Alpine.$data(document.querySelector('[x-data*="confirmModal"]'));

            modal.show({
                title: 'Hapus Aset Lancar',
                message: 'Apakah Anda yakin ingin menghapus aset lancar ini? Data yang dihapus tidak dapat dikembalikan.',
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
        /* Custom Scrollbar */
        .custom-scrollbar {
            scrollbar-color: #3b82f6 #f1f5f9;
            scrollbar-width: auto;
        }

        .custom-scrollbar::-webkit-scrollbar {
            height: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #dbeafe 0%, #3b82f6 100%);
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        }

        #scroll-top {
            padding-right: 12px;
        }

        .scrollbar-hidden {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Animations */
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

        /* Table hover effects */
        .hover\:bg-blue-50:hover {
            background-color: rgba(239, 246, 255, 0.8) !important;
        }

        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }

            .custom-scrollbar {
                overflow: visible !important;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .table {
                font-size: 0.75rem;
            }

            .table th,
            .table td {
                padding: 0.5rem 0.375rem;
            }
        }
    </style>
@endpush
