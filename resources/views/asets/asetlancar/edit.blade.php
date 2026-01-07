@extends('layouts.app')

@section('title', 'Edit Aset Lancar')

@push('page-styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.32/sweetalert2.min.css" rel="stylesheet">
    <style>
        .fade-in {
            animation: fadeIn 0.4s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-4">
        <div class="mb-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-4">
                <div class="flex items-center justify-between">

                    {{-- Breadcrumb --}}
                    <x-breadcrumbs.asetlancar current="Edit Aset Lancar" />

                </div>
            </div>
        </div>

        <!-- Form Container -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6 md:p-8">
            <form id="asetLancarForm" action="{{ route('aset-lancars.update', $asetLancar) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column (2/3) -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Rekening Section -->
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                            <h4
                                class="text-lg font-semibold text-blue-600 mb-4 pb-2 border-b-2 border-blue-600 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                Informasi Rekening
                            </h4>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Rekening Uraian <span class="text-red-600 font-bold">*</span>
                                </label>
                                <select
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('rekening_uraian_id') @enderror"
                                    id="rekening_uraian_id" name="rekening_uraian_id" required>
                                    <option value="">Pilih Rekening Uraian</option>
                                    @foreach ($rekeningUraians as $rekening)
                                        <option value="{{ $rekening->id }}"
                                            {{ old('rekening_uraian_id', $asetLancar->rekening_uraian_id) == $rekening->id ? 'selected' : '' }}
                                            data-kode="{{ $rekening->kode_rekening }}">
                                            {{ $rekening->kode_rekening }} - {{ $rekening->uraian }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('rekening_uraian_id')
                                    <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Informasi Dasar Section -->
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                            <h4
                                class="text-lg font-semibold text-blue-600 mb-4 pb-2 border-b-2 border-blue-600 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Informasi Dasar
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nama Kegiatan <span class="text-red-600 font-bold">*</span>
                                    </label>
                                    <input type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('nama_kegiatan') @enderror"
                                        id="nama_kegiatan" name="nama_kegiatan"
                                        value="{{ old('nama_kegiatan', $asetLancar->nama_kegiatan) }}"
                                        placeholder="Masukkan nama kegiatan" required>
                                    @error('nama_kegiatan')
                                        <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Barang</label>
                                    <input type="text"
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('uraian_jenis_barang') @enderror"
                                        id="uraian_jenis_barang" name="uraian_jenis_barang"
                                        value="{{ old('uraian_jenis_barang', $asetLancar->uraian_jenis_barang) }}"
                                        placeholder="Masukkan jenis barang">
                                    @error('uraian_jenis_barang')
                                        <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Uraian Kegiatan</label>
                                <textarea
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('uraian_kegiatan') @enderror"
                                    id="uraian_kegiatan" name="uraian_kegiatan" rows="4" placeholder="Masukkan uraian kegiatan">{{ old('uraian_kegiatan', $asetLancar->uraian_kegiatan) }}</textarea>
                                @error('uraian_kegiatan')
                                    <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Saldo Awal Section -->
                        <div
                            class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-6 border-2 border-cyan-500 transition-all duration-300 hover:shadow-lg">
                            <h4
                                class="text-lg font-semibold text-blue-600 mb-4 pb-2 border-b-2 border-blue-600 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3">
                                    </path>
                                </svg>
                                Saldo Awal
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Unit Barang <span class="text-red-600 font-bold">*</span>
                                    </label>
                                    <input type="number"
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('saldo_awal_unit') @enderror"
                                        id="saldo_awal_unit" name="saldo_awal_unit"
                                        value="{{ old('saldo_awal_unit', $asetLancar->saldo_awal_unit) }}" min="0"
                                        step="1" required>
                                    @error('saldo_awal_unit')
                                        <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Harga Satuan <span class="text-red-600 font-bold">*</span>
                                    </label>
                                    <div class="flex">
                                        <span
                                            class="inline-flex items-center px-3 rounded-l-lg border-2 border-r-0 border-gray-300 bg-gray-100 text-gray-700 font-semibold">Rp</span>
                                        <input type="number"
                                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('saldo_awal_harga_satuan') @enderror"
                                            id="saldo_awal_harga_satuan" name="saldo_awal_harga_satuan"
                                            value="{{ old('saldo_awal_harga_satuan', $asetLancar->saldo_awal_harga_satuan) }}"
                                            min="0" step="0.01" required>
                                    </div>
                                    @error('saldo_awal_harga_satuan')
                                        <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Total Nilai <small class="text-green-600">(Otomatis)</small>
                                    </label>
                                    <div class="flex">
                                        <span
                                            class="inline-flex items-center px-3 rounded-l-lg border-2 border-r-0 border-cyan-500 bg-blue-50 text-gray-700 font-semibold">Rp</span>
                                        <input type="text"
                                            class="w-full px-4 py-3 border-2 border-cyan-500 rounded-r-lg bg-blue-50 font-semibold text-gray-700"
                                            id="saldo_awal_total" readonly
                                            value="{{ number_format($asetLancar->saldo_awal_total, 0) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mutasi Section -->
                        <div
                            class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-6 border-2 border-cyan-500 transition-all duration-300 hover:shadow-lg">
                            <h4
                                class="text-lg font-semibold text-blue-600 mb-4 pb-2 border-b-2 border-blue-600 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                                Mutasi Aset
                            </h4>

                            <!-- Mutasi Tambah -->
                            <div class="mb-6">
                                <h6 class="text-green-600 font-semibold mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Mutasi Tambah
                                </h6>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Unit
                                            Barang</label>
                                        <input type="number"
                                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('mutasi_tambah_unit') @enderror"
                                            id="mutasi_tambah_unit" name="mutasi_tambah_unit"
                                            value="{{ old('mutasi_tambah_unit', $asetLancar->mutasi_tambah_unit) }}"
                                            min="0" step="1">
                                        @error('mutasi_tambah_unit')
                                            <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Harga
                                            Satuan</label>
                                        <div class="flex">
                                            <span
                                                class="inline-flex items-center px-3 rounded-l-lg border-2 border-r-0 border-gray-300 bg-gray-100 text-gray-700 font-semibold">Rp</span>
                                            <input type="number"
                                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('mutasi_tambah_harga_satuan') @enderror"
                                                id="mutasi_tambah_harga_satuan" name="mutasi_tambah_harga_satuan"
                                                value="{{ old('mutasi_tambah_harga_satuan', $asetLancar->mutasi_tambah_harga_satuan) }}"
                                                min="0" step="0.01">
                                        </div>
                                        @error('mutasi_tambah_harga_satuan')
                                            <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Total Nilai <small class="text-green-600">(Otomatis)</small>
                                        </label>
                                        <div class="flex">
                                            <span
                                                class="inline-flex items-center px-3 rounded-l-lg border-2 border-r-0 border-cyan-500 bg-blue-50 text-gray-700 font-semibold">Rp</span>
                                            <input type="text"
                                                class="w-full px-4 py-3 border-2 border-cyan-500 rounded-r-lg bg-blue-50 font-semibold text-gray-700"
                                                id="mutasi_tambah_nilai_total" readonly
                                                value="{{ number_format($asetLancar->mutasi_tambah_nilai_total, 0) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4 border-gray-300">

                            <!-- Mutasi Kurang -->
                            <div>
                                <h6 class="text-red-600 font-semibold mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Mutasi Kurang
                                </h6>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Unit
                                            Barang</label>
                                        <input type="number"
                                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('mutasi_kurang_unit') @enderror"
                                            id="mutasi_kurang_unit" name="mutasi_kurang_unit"
                                            value="{{ old('mutasi_kurang_unit', $asetLancar->mutasi_kurang_unit) }}"
                                            min="0" step="1">
                                        @error('mutasi_kurang_unit')
                                            <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Total Nilai <small class="text-gray-500">(Otomatis jika kosong)</small>
                                        </label>
                                        <div class="flex">
                                            <span
                                                class="inline-flex items-center px-3 rounded-l-lg border-2 border-r-0 border-gray-300 bg-gray-100 text-gray-700 font-semibold">Rp</span>
                                            <input type="number"
                                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('mutasi_kurang_nilai_total') @enderror"
                                                id="mutasi_kurang_nilai_total" name="mutasi_kurang_nilai_total"
                                                value="{{ old('mutasi_kurang_nilai_total', $asetLancar->mutasi_kurang_nilai_total) }}"
                                                min="0" step="0.01">
                                        </div>
                                        @error('mutasi_kurang_nilai_total')
                                            <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column (1/3) -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Data Saat Ini Section -->
                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-6 border-2 border-purple-300">
                            <h6 class="text-purple-700 font-semibold mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Data Saat Ini
                            </h6>
                            <div class="space-y-2">
                                <div class="bg-white rounded-lg p-3 border-l-4 border-purple-500">
                                    <span class="text-sm font-semibold text-gray-600">Rekening:</span>
                                    <p class="text-sm text-gray-800 mt-1">
                                        {{ $asetLancar->rekeningUraian->kode_rekening }} -
                                        {{ $asetLancar->rekeningUraian->uraian }}
                                    </p>
                                </div>
                                <div class="bg-white rounded-lg p-3 border-l-4 border-purple-500">
                                    <span class="text-sm font-semibold text-gray-600">Dibuat:</span>
                                    <p class="text-sm text-gray-800 mt-1">
                                        {{ $asetLancar->created_at->format('d F Y H:i') }}
                                    </p>
                                </div>
                                <div class="bg-white rounded-lg p-3 border-l-4 border-purple-500">
                                    <span class="text-sm font-semibold text-gray-600">Diperbarui:</span>
                                    <p class="text-sm text-gray-800 mt-1">
                                        {{ $asetLancar->updated_at->format('d F Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Saldo Akhir Preview Section -->
                        <div
                            class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-6 border-2 border-cyan-500 transition-all duration-300 hover:shadow-lg">
                            <h4
                                class="text-lg font-semibold text-blue-600 mb-4 pb-2 border-b-2 border-blue-600 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z">
                                    </path>
                                </svg>
                                Saldo Akhir (Preview)
                            </h4>
                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="block text-sm font-semibold text-gray-700 mb-2                                    ">
                                        Unit Barang <small class="text-yellow-600">(Otomatis)</small>
                                    </label>
                                    <div class="flex">
                                        <span
                                            class="inline-flex items-center px-3 rounded-l-lg border-2 border-r-0 border-cyan-500 bg-blue-50 text-gray-700 font-semibold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 12H4"></path>
                                            </svg>
                                        </span>
                                        <input type="text" id="saldo_akhir_unit_preview" readonly
                                            class="w-full px-4 py-3 border-2 border-cyan-500 rounded-r-lg bg-blue-50 font-semibold text-yellow-600"
                                            value="{{ number_format($asetLancar->saldo_akhir_unit) }}">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Total Nilai <small class="text-yellow-600">(Otomatis)</small>
                                    </label>
                                    <div class="flex">
                                        <span
                                            class="inline-flex items-center px-3 rounded-l-lg border-2 border-r-0 border-cyan-500 bg-blue-50 text-gray-700 font-semibold">
                                            Rp
                                        </span>
                                        <input type="text" id="saldo_akhir_total_preview" readonly
                                            class="w-full px-4 py-3 border-2 border-cyan-500 rounded-r-lg bg-blue-50 font-semibold text-yellow-600"
                                            value="{{ number_format($asetLancar->saldo_akhir_total, 0) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 p-3 bg-cyan-100 border border-cyan-300 rounded-lg text-sm text-cyan-800">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01"></path>
                                    </svg>
                                    Nilai akan diperbarui otomatis saat form diubah
                                </div>
                            </div>
                        </div>

                        <!-- Validation Rules -->
                        <div class="bg-white rounded-xl p-6 border border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b flex items-center">
                                <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Aturan Validasi
                            </h4>
                            <ul class="space-y-2 text-sm text-gray-700">
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-green-600 mr-2 mt-0.5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Harga satuan wajib diisi (saldo awal / mutasi tambah)
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-green-600 mr-2 mt-0.5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Saldo akhir unit tidak boleh negatif
                                </li>
                            </ul>
                        </div>

                        <!-- Action Buttons -->
                        <div class="bg-white rounded-xl p-6 border border-gray-200">
                            <div class="grid grid-cols-1 gap-3">
                                <button type="submit"
                                    class="w-full py-3 rounded-lg font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 transition-all duration-300 shadow-md">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- Confirm Modal --}}
    <x-notifications.confirm-modal />
@endsection
@push('page-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.32/sweetalert2.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const formatNumber = n =>
                isNaN(n) ? '0' : Math.round(n).toLocaleString('id-ID');

            function calcSaldoAwal() {
                const u = +saldo_awal_unit.value || 0;
                const h = +saldo_awal_harga_satuan.value || 0;
                saldo_awal_total.value = formatNumber(u * h);
                updateSaldoAkhir();
            }

            function calcMutasiTambah() {
                const u = +mutasi_tambah_unit.value || 0;
                const h = +mutasi_tambah_harga_satuan.value || 0;
                mutasi_tambah_nilai_total.value = formatNumber(u * h);
                updateSaldoAkhir();
            }

            function updateSaldoAkhir() {
                const awalU = +saldo_awal_unit.value || 0;
                const tambahU = +mutasi_tambah_unit.value || 0;
                const kurangU = +mutasi_kurang_unit.value || 0;

                const harga = +saldo_awal_harga_satuan.value || +mutasi_tambah_harga_satuan.value || 0;

                const akhirU = awalU + tambahU - kurangU;
                const akhirT = akhirU > 0 ? akhirU * harga : 0;

                saldo_akhir_unit_preview.value = formatNumber(akhirU);
                saldo_akhir_total_preview.value = formatNumber(akhirT);

                if (kurangU > 0 && !mutasi_kurang_nilai_total.value && harga > 0) {
                    mutasi_kurang_nilai_total.value = kurangU * harga;
                }
            }

            [
                saldo_awal_unit,
                saldo_awal_harga_satuan,
                mutasi_tambah_unit,
                mutasi_tambah_harga_satuan,
                mutasi_kurang_unit,
                mutasi_kurang_nilai_total
            ].forEach(el => {
                el.addEventListener('input', () => {
                    calcSaldoAwal();
                    calcMutasiTambah();
                });
            });

            calcSaldoAwal();
            calcMutasiTambah();
            updateSaldoAkhir();

            // Form submission handler
            const form = document.getElementById('asetLancarForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault(); // ✅ Prevent default

                    const modal = Alpine.$data(document.querySelector('[x-data*="confirmModal"]'));

                    modal.show({
                        title: 'Update Aset Lancar',
                        message: 'Apakah Anda yakin ingin menyimpan perubahan pada aset lancar ini?',
                        confirmText: 'Ya, Update',
                        cancelText: 'Batal',
                        type: 'warning',
                        onConfirm: () => {
                            form.submit(); // ✅ Submit setelah konfirmasi
                        }
                    });
                });
            }
        });
    </script>
@endpush
