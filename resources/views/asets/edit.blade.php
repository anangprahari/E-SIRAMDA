@extends('layouts.app')

@section('title', 'Edit Aset Tetap & Lainnya')

@push('page-styles')
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

        .loading-spinner {
            display: none;
        }

        .loading-spinner.active {
            display: inline-block;
        }

        .auto-filled {
            background-color: #eff6ff !important;
            border-color: #3b82f6 !important;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
@endpush
@section('content')
    <div class="container mx-auto px-4 py-4">
        <div class="mb-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-4">
                <div class="flex items-center justify-between">

                    {{-- Breadcrumb --}}
                    <x-breadcrumbs.aset current="Edit Aset Tetap & Lainnya" />

                </div>
            </div>
        </div>
        <form id="assetForm" method="POST" action="{{ route('asets.update', $aset->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Dropdown Hierarki Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center space-x-2 mb-6">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-900">Pilih Hierarki Aset</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{-- 1. Akun --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            1. Akun <span class="text-red-500">*</span>
                        </label>
                        <select id="akun" name="akun_id" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Pilih Akun</option>
                            @foreach ($akuns as $akun)
                                <option value="{{ $akun->id }}" data-kode="{{ $akun->kode }}"
                                    {{ old('akun_id', $selectedValues['akun_id'] ?? '') == $akun->id ? 'selected' : '' }}>
                                    {{ $akun->kode }} - {{ $akun->nama }}
                                </option>
                            @endforeach
                        </select>
                        <div class="loading-spinner absolute right-3 top-10" id="loading-akun">
                            <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                        <div class="error-message text-red-500 text-sm mt-1 hidden" id="error-akun"></div>
                    </div>

                    {{-- 2. Kelompok --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            2. Kelompok <span class="text-red-500">*</span>
                        </label>
                        <select id="kelompok" name="kelompok_id" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors disabled:bg-gray-50 disabled:opacity-70">
                            <option value="">Pilih Kelompok</option>
                            @foreach ($kelompoks as $kelompok)
                                <option value="{{ $kelompok->id }}" data-kode="{{ $kelompok->kode }}"
                                    {{ old('kelompok_id', $selectedValues['kelompok_id'] ?? '') == $kelompok->id ? 'selected' : '' }}>
                                    {{ $kelompok->kode }} - {{ $kelompok->nama }}
                                </option>
                            @endforeach
                        </select>
                        <div class="loading-spinner absolute right-3 top-10" id="loading-kelompok">
                            <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                        <div class="error-message text-red-500 text-sm mt-1 hidden" id="error-kelompok"></div>
                    </div>

                    {{-- 3. Jenis --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            3. Jenis <span class="text-red-500">*</span>
                        </label>
                        <select id="jenis" name="jenis_id" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors disabled:bg-gray-50 disabled:opacity-70">
                            <option value="">Pilih Jenis</option>
                            @foreach ($jenis as $j)
                                <option value="{{ $j->id }}" data-kode="{{ $j->kode }}"
                                    {{ old('jenis_id', $selectedValues['jenis_id'] ?? '') == $j->id ? 'selected' : '' }}>
                                    {{ $j->kode }} - {{ $j->nama }}
                                </option>
                            @endforeach
                        </select>
                        <div class="loading-spinner absolute right-3 top-10" id="loading-jenis">
                            <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                        <div class="error-message text-red-500 text-sm mt-1 hidden" id="error-jenis"></div>
                    </div>

                    {{-- 4. Objek --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            4. Objek <span class="text-red-500">*</span>
                        </label>
                        <select id="objek" name="objek_id" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors disabled:bg-gray-50 disabled:opacity-70">
                            <option value="">Pilih Objek</option>
                            @foreach ($objeks as $objek)
                                <option value="{{ $objek->id }}" data-kode="{{ $objek->kode }}"
                                    {{ old('objek_id', $selectedValues['objek_id'] ?? '') == $objek->id ? 'selected' : '' }}>
                                    {{ $objek->kode }} - {{ $objek->nama }}
                                </option>
                            @endforeach
                        </select>
                        <div class="loading-spinner absolute right-3 top-10" id="loading-objek">
                            <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                        <div class="error-message text-red-500 text-sm mt-1 hidden" id="error-objek"></div>
                    </div>

                    {{-- 5. Rincian Objek --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            5. Rincian Objek <span class="text-red-500">*</span>
                        </label>
                        <select id="rincian_objek" name="rincian_objek_id" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors disabled:bg-gray-50 disabled:opacity-70">
                            <option value="">Pilih Rincian Objek</option>
                            @foreach ($rincianObjeks as $rincianObjek)
                                <option value="{{ $rincianObjek->id }}" data-kode="{{ $rincianObjek->kode }}"
                                    {{ old('rincian_objek_id', $selectedValues['rincian_objek_id'] ?? '') == $rincianObjek->id ? 'selected' : '' }}>
                                    {{ $rincianObjek->kode }} - {{ $rincianObjek->nama }}
                                </option>
                            @endforeach
                        </select>
                        <div class="loading-spinner absolute right-3 top-10" id="loading-rincian-objek">
                            <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                        <div class="error-message text-red-500 text-sm mt-1 hidden" id="error-rincian-objek"></div>
                    </div>

                    {{-- 6. Sub Rincian Objek --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            6. Sub Rincian Objek <span class="text-red-500">*</span>
                        </label>
                        <select id="sub_rincian_objek" name="sub_rincian_objek_id" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors disabled:bg-gray-50 disabled:opacity-70">
                            <option value="">Pilih Sub Rincian Objek</option>
                            @foreach ($subRincianObjeks as $subRincianObjek)
                                <option value="{{ $subRincianObjek->id }}" data-kode="{{ $subRincianObjek->kode }}"
                                    {{ old('sub_rincian_objek_id', $selectedValues['sub_rincian_objek_id'] ?? '') == $subRincianObjek->id ? 'selected' : '' }}>
                                    {{ $subRincianObjek->kode }} - {{ $subRincianObjek->nama }}
                                </option>
                            @endforeach
                        </select>
                        <div class="loading-spinner absolute right-3 top-10" id="loading-sub-rincian-objek">
                            <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                        <div class="error-message text-red-500 text-sm mt-1 hidden" id="error-sub-rincian-objek">
                        </div>
                    </div>

                    {{-- 7. Sub Sub Rincian Objek --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            7. Sub Sub Rincian Objek <span class="text-red-500">*</span>
                        </label>
                        <select id="sub_sub_rincian_objek" name="sub_sub_rincian_objek_id" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors disabled:bg-gray-50 disabled:opacity-70">
                            <option value="">Pilih Sub Sub Rincian Objek</option>
                            @foreach ($subSubRincianObjeks as $subSubRincianObjek)
                                <option value="{{ $subSubRincianObjek->id }}"
                                    data-kode="{{ $subSubRincianObjek->kode }}"
                                    {{ old('sub_sub_rincian_objek_id', $selectedValues['sub_sub_rincian_objek_id'] ?? '') == $subSubRincianObjek->id ? 'selected' : '' }}>
                                    {{ $subSubRincianObjek->kode }} - {{ $subSubRincianObjek->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                        <div class="loading-spinner absolute right-3 top-10" id="loading-sub-sub-rincian-objek">
                            <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                        <div class="error-message text-red-500 text-sm mt-1 hidden" id="error-sub-sub-rincian-objek">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Display Hierarki yang dipilih --}}
            <div id="hierarchy-display"
                class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl shadow-sm border-2 border-blue-200 p-5 mb-6 fade-in"
                style="display: {{ !empty($selectedHierarchy) ? 'block' : 'none' }};">
                <div class="flex items-center space-x-2 mb-4">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="text-base font-semibold text-blue-900">Hierarki Yang Dipilih</h3>
                </div>
                <div id="hierarchy-content" class="space-y-2">
                    @if (!empty($selectedHierarchy))
                        @foreach ($selectedHierarchy as $key => $item)
                            @if ($item)
                                <div class="bg-white rounded-lg p-3 border-l-4 border-blue-500 shadow-sm">
                                    <span
                                        class="font-semibold text-blue-700">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                    <span class="text-gray-700">{{ $item->nama ?? $item->nama_barang }}</span>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- Kode Barang Preview --}}
            <div id="kode-preview"
                class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl shadow-sm border-2 border-green-300 p-5 mb-6 text-center fade-in"
                style="display: {{ old('kode_barang', $aset->kode_barang) ? 'block' : 'none' }};">
                <div class="flex items-center justify-center space-x-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                    <span class="text-gray-700 font-medium">Kode Barang:</span>
                    <span id="kode-barang-text"
                        class="text-lg font-bold text-green-700 font-mono">{{ old('kode_barang', $aset->kode_barang) }}</span>
                </div>
                <input type="hidden" name="kode_barang" id="kode_barang"
                    value="{{ old('kode_barang', $aset->kode_barang) }}">
            </div>

            {{-- Informasi Dasar Aset --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center space-x-2 mb-6">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Dasar Aset</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{-- Nama Bidang Barang --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Bidang Barang <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_bidang_barang"
                            value="{{ old('nama_bidang_barang', $aset->nama_bidang_barang) }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan nama bidang barang">
                    </div>

                    {{-- Register --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Register <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="register" value="{{ old('register', $aset->register) }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan nomor register">
                    </div>

                    {{-- Nama Jenis Barang --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Jenis Barang <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_jenis_barang"
                            value="{{ old('nama_jenis_barang', $aset->nama_jenis_barang) }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan nama jenis barang">
                    </div>

                    {{-- Asal Perolehan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Asal Perolehan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="asal_perolehan"
                            value="{{ old('asal_perolehan', $aset->asal_perolehan) }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan asal perolehan">
                    </div>

                    {{-- Tahun Perolehan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tahun Perolehan <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="tahun_perolehan" min="1900" max="{{ date('Y') }}"
                            value="{{ old('tahun_perolehan', $aset->tahun_perolehan) }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan tahun perolehan">
                    </div>

                    {{-- Satuan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Satuan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="satuan" value="{{ old('satuan', $aset->satuan) }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Contoh: Unit, Buah, Set">
                    </div>

                    {{-- Keadaan Barang --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Keadaan Barang <span class="text-red-500">*</span>
                        </label>
                        <select name="keadaan_barang" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Pilih Keadaan Barang</option>
                            <option value="B"
                                {{ old('keadaan_barang', $aset->keadaan_barang) == 'B' ? 'selected' : '' }}>
                                Baik</option>
                            <option value="KB"
                                {{ old('keadaan_barang', $aset->keadaan_barang) == 'KB' ? 'selected' : '' }}>
                                Kurang Baik</option>
                            <option value="RB"
                                {{ old('keadaan_barang', $aset->keadaan_barang) == 'RB' ? 'selected' : '' }}>
                                Rusak Berat</option>
                        </select>
                    </div>

                    {{-- Jumlah Barang --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Barang <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="jumlah_barang" min="1"
                            value="{{ old('jumlah_barang', $aset->jumlah_barang) }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan jumlah barang">
                    </div>

                    {{-- Harga Satuan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Harga Satuan <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="harga_satuan" min="0" step="0.01"
                            value="{{ old('harga_satuan', $aset->harga_satuan) }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan harga satuan">
                    </div>
                </div>
            </div>

            {{-- Informasi Tambahan --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center space-x-2 mb-6">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Tambahan</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{-- Merk / Type --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Merk / Type</label>
                        <input type="text" name="merk_type" value="{{ old('merk_type', $aset->merk_type) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan merk atau type barang">
                    </div>

                    {{-- No. Sertifikat --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. Sertifikat</label>
                        <input type="text" name="no_sertifikat"
                            value="{{ old('no_sertifikat', $aset->no_sertifikat) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan nomor sertifikat">
                    </div>

                    {{-- No. Plat Kendaraan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. Plat Kendaraan</label>
                        <input type="text" name="no_plat_kendaraan"
                            value="{{ old('no_plat_kendaraan', $aset->no_plat_kendaraan) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan nomor plat kendaraan">
                    </div>

                    {{-- No. Pabrik --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. Pabrik</label>
                        <input type="text" name="no_pabrik" value="{{ old('no_pabrik', $aset->no_pabrik) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan nomor pabrik">
                    </div>

                    {{-- No. Casis --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. Casis</label>
                        <input type="text" name="no_casis" value="{{ old('no_casis', $aset->no_casis) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan nomor casis">
                    </div>

                    {{-- Bahan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bahan</label>
                        <input type="text" name="bahan" value="{{ old('bahan', $aset->bahan) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan bahan barang">
                    </div>

                    {{-- Ukuran Barang / Konstruksi --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran Barang / Konstruksi</label>
                        <input type="text" name="ukuran_barang_konstruksi"
                            value="{{ old('ukuran_barang_konstruksi', $aset->ukuran_barang_konstruksi) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan ukuran barang atau konstruksi">
                    </div>

                    {{-- Bukti Barang --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Barang</label>
                        <input type="file" name="bukti_barang" accept="image/jpeg,image/png,image/jpg,image/gif"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <small class="text-xs text-gray-500 mt-1 block">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                        @if ($aset->bukti_barang_path)
                            <div class="mt-2">
                                <small class="text-xs text-gray-500">File saat ini: <a
                                        href="{{ Storage::url($aset->bukti_barang_path) }}" target="_blank"
                                        class="text-blue-600 hover:underline">Lihat File</a></small>
                            </div>
                        @endif
                    </div>

                    {{-- Bukti Berita --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Berita</label>
                        <input type="file" name="bukti_berita" accept="application/pdf"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <small class="text-xs text-gray-500 mt-1 block">Format: PDF. Maksimal 10MB</small>
                        @if ($aset->bukti_berita_path)
                            <div class="mt-2">
                                <small class="text-xs text-gray-500">File saat ini: <a
                                        href="{{ Storage::url($aset->bukti_berita_path) }}" target="_blank"
                                        class="text-blue-600 hover:underline">Lihat File</a></small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end space-x-4 pb-6">
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl">
                    <span>Update Aset</span>
                </button>
            </div>
        </form>
    </div>
@endsection

@push('page-scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Variabel global untuk menyimpan hierarki yang dipilih
        let selectedHierarchy = {
            akun: @json($selectedHierarchy['akun'] ?? null),
            kelompok: @json($selectedHierarchy['kelompok'] ?? null),
            jenis: @json($selectedHierarchy['jenis'] ?? null),
            objek: @json($selectedHierarchy['objek'] ?? null),
            rincianObjek: @json($selectedHierarchy['rincian_objek'] ?? null),
            subRincianObjek: @json($selectedHierarchy['sub_rincian_objek'] ?? null),
            subSubRincianObjek: @json($selectedHierarchy['sub_sub_rincian_objek'] ?? null)
        };

        // Data untuk pre-populate (dari controller)
        const selectedValues = @json($selectedValues ?? []);
        const isEditMode = true;
        let isInitializing = true;

        document.addEventListener('DOMContentLoaded', function() {
            // Setup event listeners
            setupEventListeners();

            // Pre-load existing hierarchy pada saat edit
            setTimeout(() => {
                preLoadExistingHierarchy();
            }, 100);

            // Show validation errors if any
            @if ($errors->any())
                let errorMessages = '';
                @foreach ($errors->all() as $error)
                    errorMessages += '{{ $error }}\n';
                @endforeach
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: errorMessages,
                });
            @endif
        });

        function preLoadExistingHierarchy() {
            isInitializing = true;

            // Set akun yang sudah terpilih dari awal
            if (selectedValues.akun_id) {
                document.getElementById('akun').value = selectedValues.akun_id;
                selectedHierarchy.akun = getSelectedOption(document.getElementById('akun'));

                // Load kelompok
                loadKelompoks(selectedValues.akun_id, () => {
                    if (selectedValues.kelompok_id) {
                        document.getElementById('kelompok').value = selectedValues.kelompok_id;
                        selectedHierarchy.kelompok = getSelectedOption(document.getElementById('kelompok'));

                        loadJenis(selectedValues.kelompok_id, () => {
                            if (selectedValues.jenis_id) {
                                document.getElementById('jenis').value = selectedValues.jenis_id;
                                selectedHierarchy.jenis = getSelectedOption(document.getElementById(
                                    'jenis'));

                                loadObjeks(selectedValues.jenis_id, () => {
                                    if (selectedValues.objek_id) {
                                        document.getElementById('objek').value = selectedValues
                                            .objek_id;
                                        selectedHierarchy.objek = getSelectedOption(document
                                            .getElementById('objek'));

                                        loadRincianObjeks(selectedValues.objek_id, () => {
                                            if (selectedValues.rincian_objek_id) {
                                                document.getElementById('rincian_objek')
                                                    .value = selectedValues
                                                    .rincian_objek_id;
                                                selectedHierarchy.rincianObjek =
                                                    getSelectedOption(document
                                                        .getElementById('rincian_objek'));

                                                loadSubRincianObjeks(selectedValues
                                                    .rincian_objek_id, () => {
                                                        if (selectedValues
                                                            .sub_rincian_objek_id) {
                                                            document.getElementById(
                                                                    'sub_rincian_objek')
                                                                .value = selectedValues
                                                                .sub_rincian_objek_id;
                                                            selectedHierarchy
                                                                .subRincianObjek =
                                                                getSelectedOption(
                                                                    document
                                                                    .getElementById(
                                                                        'sub_rincian_objek'
                                                                    ));

                                                            loadSubSubRincianObjeks(
                                                                selectedValues
                                                                .sub_rincian_objek_id,
                                                                () => {
                                                                    if (selectedValues
                                                                        .sub_sub_rincian_objek_id
                                                                    ) {
                                                                        document
                                                                            .getElementById(
                                                                                'sub_sub_rincian_objek'
                                                                            )
                                                                            .value =
                                                                            selectedValues
                                                                            .sub_sub_rincian_objek_id;
                                                                        selectedHierarchy
                                                                            .subSubRincianObjek =
                                                                            getSelectedOption(
                                                                                document
                                                                                .getElementById(
                                                                                    'sub_sub_rincian_objek'
                                                                                )
                                                                            );
                                                                    }
                                                                    finishInitialization
                                                                        ();
                                                                });
                                                        } else {
                                                            finishInitialization();
                                                        }
                                                    });
                                            } else {
                                                finishInitialization();
                                            }
                                        });
                                    } else {
                                        finishInitialization();
                                    }
                                });
                            } else {
                                finishInitialization();
                            }
                        });
                    } else {
                        finishInitialization();
                    }
                });
            } else {
                finishInitialization();
            }
        }

        // Tambahkan function baru untuk menyelesaikan inisialisasi
        function finishInitialization() {
            // PENTING: Reset flag isInitializing agar event listener bisa berfungsi
            isInitializing = false;

            // Update display dan kode barang
            updateHierarchyDisplay();
            updateKodeBarang();

            console.log('Initialization completed. Event listeners are now active.');
        }

        function setupEventListeners() {
            // Event listeners untuk setiap dropdown
            document.getElementById('akun')?.addEventListener('change', function() {
                if (isInitializing) return;

                const akunId = this.value;
                selectedHierarchy.akun = getSelectedOption(this);

                if (akunId) {
                    loadKelompoks(akunId, () => {
                        resetDropdowns(['kelompok', 'jenis', 'objek', 'rincian_objek', 'sub_rincian_objek',
                            'sub_sub_rincian_objek'
                        ]);
                        updateHierarchyDisplay();
                        updateKodeBarang();
                    });
                } else {
                    resetAllDropdowns();
                }
            });

            document.getElementById('kelompok')?.addEventListener('change', function() {
                if (isInitializing) return;

                const kelompokId = this.value;
                selectedHierarchy.kelompok = getSelectedOption(this);

                if (kelompokId) {
                    loadJenis(kelompokId, () => {
                        resetDropdowns(['jenis', 'objek', 'rincian_objek', 'sub_rincian_objek',
                            'sub_sub_rincian_objek'
                        ]);
                        updateHierarchyDisplay();
                        updateKodeBarang();
                    });
                } else {
                    resetDropdowns(['jenis', 'objek', 'rincian_objek', 'sub_rincian_objek',
                        'sub_sub_rincian_objek'
                    ]);
                    updateHierarchyDisplay();
                    updateKodeBarang();
                }
            });

            document.getElementById('jenis')?.addEventListener('change', function() {
                if (isInitializing) return;

                const jenisId = this.value;
                selectedHierarchy.jenis = getSelectedOption(this);

                if (jenisId) {
                    loadObjeks(jenisId, () => {
                        resetDropdowns(['objek', 'rincian_objek', 'sub_rincian_objek',
                            'sub_sub_rincian_objek'
                        ]);
                        updateHierarchyDisplay();
                        updateKodeBarang();
                    });
                } else {
                    resetDropdowns(['objek', 'rincian_objek', 'sub_rincian_objek', 'sub_sub_rincian_objek']);
                    updateHierarchyDisplay();
                    updateKodeBarang();
                }
            });

            document.getElementById('objek')?.addEventListener('change', function() {
                if (isInitializing) return;

                const objekId = this.value;
                selectedHierarchy.objek = getSelectedOption(this);

                if (objekId) {
                    loadRincianObjeks(objekId, () => {
                        resetDropdowns(['rincian_objek', 'sub_rincian_objek', 'sub_sub_rincian_objek']);
                        updateHierarchyDisplay();
                        updateKodeBarang();
                    });
                } else {
                    resetDropdowns(['rincian_objek', 'sub_rincian_objek', 'sub_sub_rincian_objek']);
                    updateHierarchyDisplay();
                    updateKodeBarang();
                }
            });

            document.getElementById('rincian_objek')?.addEventListener('change', function() {
                if (isInitializing) return;

                const rincianObjekId = this.value;
                selectedHierarchy.rincianObjek = getSelectedOption(this);

                if (rincianObjekId) {
                    loadSubRincianObjeks(rincianObjekId, () => {
                        resetDropdowns(['sub_rincian_objek', 'sub_sub_rincian_objek']);
                        updateHierarchyDisplay();
                        updateKodeBarang();
                    });
                } else {
                    resetDropdowns(['sub_rincian_objek', 'sub_sub_rincian_objek']);
                    updateHierarchyDisplay();
                    updateKodeBarang();
                }
            });

            document.getElementById('sub_rincian_objek')?.addEventListener('change', function() {
                if (isInitializing) return;

                const subRincianObjekId = this.value;
                selectedHierarchy.subRincianObjek = getSelectedOption(this);

                // Auto-fill Nama Bidang Barang
                if (selectedHierarchy.subRincianObjek && selectedHierarchy.subRincianObjek.nama) {
                    const namaBidangBarangInput = document.querySelector('input[name="nama_bidang_barang"]');
                    if (namaBidangBarangInput) {
                        namaBidangBarangInput.value = selectedHierarchy.subRincianObjek.nama;
                        namaBidangBarangInput.classList.add('auto-filled');
                    }
                } else {
                    const namaBidangBarangInput = document.querySelector('input[name="nama_bidang_barang"]');
                    if (namaBidangBarangInput) {
                        namaBidangBarangInput.value = '';
                        namaBidangBarangInput.classList.remove('auto-filled');
                    }
                }

                if (subRincianObjekId) {
                    loadSubSubRincianObjeks(subRincianObjekId, () => {
                        resetDropdowns(['sub_sub_rincian_objek']);
                        updateHierarchyDisplay();
                        updateKodeBarang();
                    });
                } else {
                    resetDropdowns(['sub_sub_rincian_objek']);
                    updateHierarchyDisplay();
                    updateKodeBarang();
                }
            });

            document.getElementById('sub_sub_rincian_objek')?.addEventListener('change', function() {
                if (isInitializing) return;

                selectedHierarchy.subSubRincianObjek = getSelectedOption(this);
                updateHierarchyDisplay();
                updateKodeBarang();
            });

            // Form submission handler
            document.getElementById('assetForm')?.addEventListener('submit', function(e) {
                const isValid = validateDropdowns();
                if (!isValid) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Harap lengkapi semua level hierarki aset yang wajib diisi!',
                    });
                    return;
                }

                // Show loading
                Swal.fire({
                    title: 'Memperbarui...',
                    text: 'Sedang memproses data aset',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            });

            // Event listener untuk keadaan barang
            const keadaanBarangSelect = document.querySelector('select[name="keadaan_barang"]');
            if (keadaanBarangSelect) {
                keadaanBarangSelect.addEventListener('change', function() {
                    handleKeadaanBarangChange(this.value);
                });

                // Trigger untuk nilai yang sudah ada saat halaman dimuat
                if (keadaanBarangSelect.value) {
                    setTimeout(() => {
                        handleKeadaanBarangChange(keadaanBarangSelect.value);
                    }, 500);
                }
            }
        }

        function getSelectedOption(selectElement) {
            if (!selectElement) return null;

            const selectedOption = selectElement.options[selectElement.selectedIndex];
            if (selectedOption && selectedOption.value) {
                const kode = selectedOption.dataset.kode || '';
                let nama = selectedOption.textContent;
                if (nama.includes(' - ')) {
                    nama = nama.split(' - ').slice(1).join(' - ');
                }

                return {
                    id: selectedOption.value,
                    nama: nama,
                    kode: kode
                };
            }
            return null;
        }

        function loadKelompoks(akunId, callback = null) {
            const select = document.getElementById('kelompok');
            if (!select) return;

            showLoading('kelompok');

            fetch(`/api/asets/kelompoks/${akunId}`)
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        populateSelect(select, data.data, 'Pilih Kelompok');
                        select.disabled = data.data.length === 0;
                        hideError('kelompok');
                        if (callback) callback();
                    } else {
                        showError('kelompok', data.message || 'Gagal memuat data kelompok');
                        select.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error loading kelompoks:', error);
                    showError('kelompok', 'Terjadi kesalahan saat memuat data');
                    select.disabled = true;
                })
                .finally(() => {
                    hideLoading('kelompok');
                });
        }

        function loadJenis(kelompokId, callback = null) {
            const select = document.getElementById('jenis');
            if (!select) return;

            showLoading('jenis');

            fetch(`/api/asets/jenis/${kelompokId}`)
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        populateSelect(select, data.data, 'Pilih Jenis');
                        select.disabled = data.data.length === 0;
                        hideError('jenis');
                        if (callback) callback();
                    } else {
                        showError('jenis', data.message || 'Gagal memuat data jenis');
                        select.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error loading jenis:', error);
                    showError('jenis', 'Terjadi kesalahan saat memuat data');
                    select.disabled = true;
                })
                .finally(() => {
                    hideLoading('jenis');
                });
        }

        function loadObjeks(jenisId, callback = null) {
            const select = document.getElementById('objek');
            if (!select) return;

            showLoading('objek');

            fetch(`/api/asets/objeks/${jenisId}`)
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        populateSelect(select, data.data, 'Pilih Objek');
                        select.disabled = data.data.length === 0;
                        hideError('objek');
                        if (callback) callback();
                    } else {
                        showError('objek', data.message || 'Gagal memuat data objek');
                        select.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error loading objeks:', error);
                    showError('objek', 'Terjadi kesalahan saat memuat data');
                    select.disabled = true;
                })
                .finally(() => {
                    hideLoading('objek');
                });
        }

        function loadRincianObjeks(objekId, callback = null) {
            const select = document.getElementById('rincian_objek');
            if (!select) return;

            showLoading('rincian-objek');

            fetch(`/api/asets/rincian-objeks/${objekId}`)
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        populateSelect(select, data.data, 'Pilih Rincian Objek');
                        select.disabled = data.data.length === 0;
                        hideError('rincian-objek');
                        if (callback) callback();
                    } else {
                        showError('rincian-objek', data.message || 'Gagal memuat data rincian objek');
                        select.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error loading rincian objeks:', error);
                    showError('rincian-objek', 'Terjadi kesalahan saat memuat data');
                    select.disabled = true;
                })
                .finally(() => {
                    hideLoading('rincian-objek');
                });
        }

        function loadSubRincianObjeks(rincianObjekId, callback = null) {
            const select = document.getElementById('sub_rincian_objek');
            if (!select) return;

            showLoading('sub-rincian-objek');

            fetch(`/api/asets/sub-rincian-objeks/${rincianObjekId}`)
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        populateSelect(select, data.data, 'Pilih Sub Rincian Objek');
                        select.disabled = data.data.length === 0;
                        hideError('sub-rincian-objek');
                        if (callback) callback();
                    } else {
                        showError('sub-rincian-objek', data.message || 'Gagal memuat data sub rincian objek');
                        select.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error loading sub rincian objeks:', error);
                    showError('sub-rincian-objek', 'Terjadi kesalahan saat memuat data');
                    select.disabled = true;
                })
                .finally(() => {
                    hideLoading('sub-rincian-objek');
                });
        }

        function loadSubSubRincianObjeks(subRincianObjekId, callback = null) {
            const select = document.getElementById('sub_sub_rincian_objek');
            if (!select) return;

            showLoading('sub-sub-rincian-objek');

            fetch(`/api/asets/sub-sub-rincian-objeks/${subRincianObjekId}`)
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        populateSelect(select, data.data, 'Pilih Sub Sub Rincian Objek', 'nama_barang');
                        select.disabled = data.data.length === 0;
                        hideError('sub-sub-rincian-objek');
                        if (callback) callback();
                    } else {
                        showError('sub-sub-rincian-objek', data.message || 'Gagal memuat data sub sub rincian objek');
                        select.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error loading sub sub rincian objeks:', error);
                    showError('sub-sub-rincian-objek', 'Terjadi kesalahan saat memuat data');
                    select.disabled = true;
                })
                .finally(() => {
                    hideLoading('sub-sub-rincian-objek');
                });
        }

        function populateSelect(select, data, placeholder, nameField = 'nama') {
            if (!select) return;

            select.innerHTML = `<option value="">${placeholder}</option>`;

            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = `${item.kode} - ${item[nameField]}`;
                option.dataset.kode = item.kode;
                select.appendChild(option);
            });
        }

        function resetDropdowns(dropdownIds) {
            dropdownIds.forEach(id => {
                const select = document.getElementById(id);
                if (select) {
                    select.innerHTML = '<option value="">Pilih...</option>';
                    select.disabled = true;
                    const errorId = id.replace(/_/g, '-');
                    hideError(errorId);

                    const key = getHierarchyKey(id);
                    if (key && selectedHierarchy[key]) {
                        delete selectedHierarchy[key];
                    }

                    if (id === 'sub_rincian_objek') {
                        const namaBidangBarangInput = document.querySelector('input[name="nama_bidang_barang"]');
                        if (namaBidangBarangInput) {
                            namaBidangBarangInput.value = '';
                            namaBidangBarangInput.classList.remove('auto-filled');
                        }
                    }
                }
            });
        }

        function getHierarchyKey(dropdownId) {
            const mapping = {
                'akun': 'akun',
                'kelompok': 'kelompok',
                'jenis': 'jenis',
                'objek': 'objek',
                'rincian_objek': 'rincianObjek',
                'sub_rincian_objek': 'subRincianObjek',
                'sub_sub_rincian_objek': 'subSubRincianObjek'
            };
            return mapping[dropdownId];
        }

        function resetAllDropdowns() {
            resetDropdowns(['kelompok', 'jenis', 'objek', 'rincian_objek', 'sub_rincian_objek', 'sub_sub_rincian_objek']);
            hideKodePreview();

            const currentAkun = selectedHierarchy.akun;
            selectedHierarchy = {};
            if (currentAkun) {
                selectedHierarchy.akun = currentAkun;
            }

            updateHierarchyDisplay();
        }

        function showLoading(type) {
            const loadingElement = document.getElementById(`loading-${type}`);
            if (loadingElement) loadingElement.classList.add('active');
        }

        function hideLoading(type) {
            const loadingElement = document.getElementById(`loading-${type}`);
            if (loadingElement) loadingElement.classList.remove('active');
        }

        function showError(field, message) {
            const errorElement = document.getElementById(`error-${field}`);
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
            }
        }

        function hideError(field) {
            const errorElement = document.getElementById(`error-${field}`);
            if (errorElement) errorElement.classList.add('hidden');
        }

        function validateDropdowns() {
            let isValid = true;
            const dropdowns = ['akun', 'kelompok', 'jenis', 'objek', 'rincian_objek', 'sub_rincian_objek',
                'sub_sub_rincian_objek'
            ];

            dropdowns.forEach(dropdownId => {
                const select = document.getElementById(dropdownId);
                if (select && !select.value) {
                    const fieldName = dropdownId.replace(/_/g, '-');
                    showError(fieldName, 'Field ini wajib diisi');
                    isValid = false;
                }
            });

            return isValid;
        }

        function updateKodeBarang() {
            const keadaanBarangSelect = document.querySelector('select[name="keadaan_barang"]');

            if (keadaanBarangSelect && keadaanBarangSelect.value === 'RB') {
                handleKeadaanBarangChange('RB');
                return;
            }

            if (selectedHierarchy.akun && selectedHierarchy.kelompok && selectedHierarchy.jenis &&
                selectedHierarchy.objek && selectedHierarchy.rincianObjek &&
                selectedHierarchy.subRincianObjek && selectedHierarchy.subSubRincianObjek) {

                const kodeBarang = generateKodeFromHierarchy();

                if (kodeBarang) {
                    document.getElementById('kode-barang-text').textContent = kodeBarang;
                    document.getElementById('kode_barang').value = kodeBarang;
                    document.getElementById('kode-preview').style.display = 'block';
                }
            } else {
                const existingKode = "{{ old('kode_barang', $aset->kode_barang) }}";
                if (existingKode) {
                    document.getElementById('kode-barang-text').textContent = existingKode;
                    document.getElementById('kode_barang').value = existingKode;
                    document.getElementById('kode-preview').style.display = 'block';
                } else {
                    hideKodePreview();
                }
            }
        }

        function generateKodeFromHierarchy() {
            try {
                if (!selectedHierarchy.subSubRincianObjek?.kode) {
                    return null;
                }

                let kode = selectedHierarchy.subSubRincianObjek.kode;
                return kode;

            } catch (error) {
                console.error('Error generating kode barang:', error);
                return null;
            }
        }

        function hideKodePreview() {
            const preview = document.getElementById('kode-preview');
            const kodeInput = document.getElementById('kode_barang');
            if (preview) preview.style.display = 'none';
            if (kodeInput) kodeInput.value = '';
        }

        function updateHierarchyDisplay() {
            const hierarchyDisplay = document.getElementById('hierarchy-display');
            const hierarchyContent = document.getElementById('hierarchy-content');

            if (!hierarchyDisplay || !hierarchyContent) return;

            let content = '';

            if (selectedHierarchy.akun) {
                content +=
                    `<div class="bg-white rounded-lg p-3 border-l-4 border-blue-500 shadow-sm"><span class="font-semibold text-blue-700">Akun:</span> <span class="text-gray-700">${selectedHierarchy.akun.nama}</span></div>`;
            }
            if (selectedHierarchy.kelompok) {
                content +=
                    `<div class="bg-white rounded-lg p-3 border-l-4 border-blue-500 shadow-sm"><span class="font-semibold text-blue-700">Kelompok:</span> <span class="text-gray-700">${selectedHierarchy.kelompok.nama}</span></div>`;
            }
            if (selectedHierarchy.jenis) {
                content +=
                    `<div class="bg-white rounded-lg p-3 border-l-4 border-blue-500 shadow-sm"><span class="font-semibold text-blue-700">Jenis:</span> <span class="text-gray-700">${selectedHierarchy.jenis.nama}</span></div>`;
            }
            if (selectedHierarchy.objek) {
                content +=
                    `<div class="bg-white rounded-lg p-3 border-l-4 border-blue-500 shadow-sm"><span class="font-semibold text-blue-700">Objek:</span> <span class="text-gray-700">${selectedHierarchy.objek.nama}</span></div>`;
            }
            if (selectedHierarchy.rincianObjek) {
                content +=
                    `<div class="bg-white rounded-lg p-3 border-l-4 border-blue-500 shadow-sm"><span class="font-semibold text-blue-700">Rincian Objek:</span> <span class="text-gray-700">${selectedHierarchy.rincianObjek.nama}</span></div>`;
            }
            if (selectedHierarchy.subRincianObjek) {
                content +=
                    `<div class="bg-white rounded-lg p-3 border-l-4 border-blue-500 shadow-sm"><span class="font-semibold text-blue-700">Sub Rincian Objek:</span> <span class="text-gray-700">${selectedHierarchy.subRincianObjek.nama}</span></div>`;
            }
            if (selectedHierarchy.subSubRincianObjek) {
                content +=
                    `<div class="bg-white rounded-lg p-3 border-l-4 border-blue-500 shadow-sm"><span class="font-semibold text-blue-700">Sub Sub Rincian:</span> <span class="text-gray-700">${selectedHierarchy.subSubRincianObjek.nama}</span></div>`;
            }

            if (content) {
                hierarchyContent.innerHTML = content;
                hierarchyDisplay.style.display = 'block';
            } else {
                hierarchyDisplay.style.display = 'none';
            }
        }

        function handleKeadaanBarangChange(keadaanBarang) {
            const kodeBarangDisplay = document.getElementById('kode-barang-text');
            const kodeBarangInput = document.getElementById('kode_barang');
            const kodePreview = document.getElementById('kode-preview');

            if (keadaanBarang === 'RB') {
                const kodeRusakBerat = '1.5.4.01.01.01.005';

                if (kodeBarangDisplay) {
                    kodeBarangDisplay.textContent = kodeRusakBerat;
                    kodeBarangDisplay.classList.remove('text-green-700');
                    kodeBarangDisplay.classList.add('text-red-700');
                }

                if (kodeBarangInput) {
                    kodeBarangInput.value = kodeRusakBerat;
                }

                if (kodePreview) {
                    kodePreview.style.display = 'block';
                    kodePreview.classList.remove('from-green-50', 'to-emerald-50', 'border-green-300');
                    kodePreview.classList.add('from-red-50', 'to-rose-50', 'border-red-300');

                    const icon = kodePreview.querySelector('svg');
                    if (icon) {
                        icon.classList.remove('text-green-600');
                        icon.classList.add('text-red-600');
                    }
                }

                showRusakBeratWarning();

            } else if (keadaanBarang === 'B' || keadaanBarang === 'KB') {
                updateKodeBarang();

                if (kodeBarangDisplay) {
                    kodeBarangDisplay.classList.remove('text-red-700');
                    kodeBarangDisplay.classList.add('text-green-700');
                }

                if (kodePreview) {
                    kodePreview.classList.remove('from-red-50', 'to-rose-50', 'border-red-300');
                    kodePreview.classList.add('from-green-50', 'to-emerald-50', 'border-green-300');

                    const icon = kodePreview.querySelector('svg');
                    if (icon) {
                        icon.classList.remove('text-red-600');
                        icon.classList.add('text-green-600');
                    }
                }

                hideRusakBeratWarning();
            }
        }

        function showRusakBeratWarning() {
            hideRusakBeratWarning();

            const kodePreview = document.getElementById('kode-preview');
            if (kodePreview) {
                const warningDiv = document.createElement('div');
                warningDiv.id = 'rusak-berat-warning';
                warningDiv.className = 'bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-4';
                warningDiv.innerHTML = `
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <strong class="text-yellow-900">Perhatian:</strong>
                            <p class="text-yellow-800 text-sm mt-1">Aset dengan keadaan "Rusak Berat" akan menggunakan kode barang khusus dan akan diurutkan di bagian paling bawah daftar aset.</p>
                        </div>
                    </div>
                `;

                kodePreview.parentNode.insertBefore(warningDiv, kodePreview.nextSibling);
            }
        }

        function hideRusakBeratWarning() {
            const existingWarning = document.getElementById('rusak-berat-warning');
            if (existingWarning) {
                existingWarning.remove();
            }
        }

        function goBack() {
            window.history.back();
        }
    </script>
@endpush
