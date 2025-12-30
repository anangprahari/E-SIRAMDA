@extends('layouts.app')

@section('title', 'Tambah Aset Tetap & Lainnya')

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
                    <x-breadcrumbs.aset current="Tambah Aset Tetap & Lainnya" />

                </div>
            </div>
        </div>
        <form id="assetForm" method="POST" action="{{ route('asets.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Dropdown Hierarki Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-4">
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
                                <option value="{{ $akun->id }}" data-kode="{{ $akun->kode }}">
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
                        <select id="kelompok" name="kelompok_id" disabled required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors disabled:bg-gray-50 disabled:opacity-70">
                            <option value="">Pilih Kelompok</option>
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
                        <select id="jenis" name="jenis_id" disabled required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors disabled:bg-gray-50 disabled:opacity-70">
                            <option value="">Pilih Jenis</option>
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
                        <select id="objek" name="objek_id" disabled required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors disabled:bg-gray-50 disabled:opacity-70">
                            <option value="">Pilih Objek</option>
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
                        <select id="rincian_objek" name="rincian_objek_id" disabled required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors disabled:bg-gray-50 disabled:opacity-70">
                            <option value="">Pilih Rincian Objek</option>
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
                        <select id="sub_rincian_objek" name="sub_rincian_objek_id" disabled required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors disabled:bg-gray-50 disabled:opacity-70">
                            <option value="">Pilih Sub Rincian Objek</option>
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
                        <div class="error-message text-red-500 text-sm mt-1 hidden" id="error-sub-rincian-objek"></div>
                    </div>

                    {{-- 7. Sub Sub Rincian Objek --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            7. Sub Sub Rincian Objek <span class="text-red-500">*</span>
                        </label>
                        <select id="sub_sub_rincian_objek" name="sub_sub_rincian_objek_id" disabled required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors disabled:bg-gray-50 disabled:opacity-70">
                            <option value="">Pilih Sub Sub Rincian Objek</option>
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
                class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl shadow-sm border-2 border-blue-200 p-5 mb-4 hidden fade-in">
                <div class="flex items-center space-x-2 mb-4">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="text-base font-semibold text-blue-900">Hierarki Yang Dipilih</h3>
                </div>
                <div id="hierarchy-content" class="space-y-2"></div>
            </div>

            {{-- Kode Barang Preview --}}
            <div id="kode-preview"
                class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl shadow-sm border-2 border-green-300 p-5 mb-4 text-center hidden fade-in">
                <div class="flex items-center justify-center space-x-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                    <span class="text-gray-700 font-medium">Kode Barang:</span>
                    <span id="kode-barang-text" class="text-lg font-bold text-green-700 font-mono">-</span>
                </div>
                <input type="hidden" name="kode_barang" id="kode_barang">
            </div>

            {{-- Informasi Dasar Aset --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-4">
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
                        <input type="text" name="nama_bidang_barang" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan nama bidang barang">
                    </div>

                    {{-- Register --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Register <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="register" name="register" required maxlength="3" pattern="[0-9]{3}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Akan otomatis terisi"
                            title="Register harus berupa 3 digit angka (contoh: 001, 021, 100)">
                        <p id="register_info" class="text-xs text-gray-500 mt-1"></p>
                        <small class="text-xs text-gray-500">Register akan otomatis diatur berdasarkan kode barang</small>
                    </div>

                    {{-- Nama Jenis Barang --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Jenis Barang <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_jenis_barang" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan nama jenis barang">
                    </div>

                    {{-- Asal Perolehan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Asal Perolehan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="asal_perolehan" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan asal perolehan">
                    </div>

                    {{-- Tahun Perolehan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tahun Perolehan <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="tahun_perolehan" min="1900" max="{{ date('Y') }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan tahun perolehan">
                    </div>

                    {{-- Satuan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Satuan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="satuan" required
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
                            <option value="B">Baik</option>
                            <option value="KB">Kurang Baik</option>
                            <option value="RB">Rusak Berat</option>
                        </select>
                    </div>

                    {{-- Jumlah Barang --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Barang <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="jumlah_barang" min="1" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan jumlah barang">
                    </div>

                    {{-- Harga Satuan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Harga Satuan <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="harga_satuan" min="0" step="0.01" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan harga satuan">
                    </div>

                    {{-- Lokasi Barang --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Barang</label>
                        <input type="text" name="lokasi_barang" value="{{ old('lokasi_barang') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Contoh: Gudang A, Lantai 2">
                    </div>

                    {{-- Ruangan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ruangan</label>
                        <select name="ruangan"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Pilih Ruangan</option>
                            <option value="Seksi Pengembangan Aplikasi">Seksi Pengembangan Aplikasi</option>
                            <option value="Seksi Pengembangan E-Government">Seksi Pengembangan E-Government</option>
                            <option value="Seksi Tata Kelola E-Government">Seksi Tata Kelola E-Government</option>
                            <option value="Seksi Pengelolaan dan Dokumentasi Informasi">Seksi Pengelolaan dan Dokumentasi
                                Informasi</option>
                            <option value="Seksi Publikasi">Seksi Publikasi</option>
                            <option value="Seksi Kemitraan Informasi dan Komunikasi Publik">Seksi Kemitraan Informasi dan
                                Komunikasi Publik</option>
                            <option value="Seksi Tata Kelola dan Operasional Persandian">Seksi Tata Kelola dan Operasional
                                Persandian</option>
                            <option value="Seksi Pengawasan dan Evaluasi Penyelenggaraan Persandian">Seksi Pengawasan dan
                                Evaluasi Penyelenggaraan Persandian</option>
                            <option value="Seksi Teknologi Informasi dan Komunikasi">Seksi Teknologi Informasi dan
                                Komunikasi</option>
                            <option value="Subbagian Umum dan Kepegawaian">Subbagian Umum dan Kepegawaian</option>
                            <option value="Subbagian Keuangan dan Aset">Subbagian Keuangan dan Aset</option>
                            <option value="Subbagian Program dan Pelaporan">Subbagian Program dan Pelaporan</option>
                            <option value="Kelompok Jabatan Fungsional">Kelompok Jabatan Fungsional</option>
                            <option value="Unit Pelaksana Teknis (UPT)">Unit Pelaksana Teknis (UPT)</option>
                            <option value="Sekretariat PPID / Pengelola Data">Sekretariat PPID / Pengelola Data</option>
                        </select>
                    </div>

                    {{-- Keterangan --}}
                    <div class="md:col-span-2 lg:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" rows="3"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Tambahkan keterangan tambahan jika diperlukan">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Register Status Panel --}}
            <div id="register_status_panel" class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4 hidden fade-in">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1">
                        <h6 class="font-semibold text-blue-900 mb-2">Informasi Register</h6>
                        <div id="register_status_content" class="text-sm text-blue-700"></div>
                    </div>
                </div>
            </div>

            {{-- Informasi Tambahan --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-4">
                <div class="flex items-center space-x-2 mb-6">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Tambahan</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Merk / Type</label>
                        <input type="text" name="merk_type"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan merk atau type barang">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. Sertifikat</label>
                        <input type="text" name="no_sertifikat"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan nomor sertifikat">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. Plat Kendaraan</label>
                        <input type="text" name="no_plat_kendaraan"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan nomor plat kendaraan">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. Pabrik</label>
                        <input type="text" name="no_pabrik"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan nomor pabrik">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. Casis</label>
                        <input type="text" name="no_casis"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan nomor casis">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bahan</label>
                        <input type="text" name="bahan"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan bahan barang">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran Barang / Konstruksi</label>
                        <input type="text" name="ukuran_barang_konstruksi"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Masukkan ukuran barang atau konstruksi">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Barang</label>
                        <input type="file" name="bukti_barang" accept="image/jpeg,image/png,image/jpg,image/gif"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <small class="text-xs text-gray-500 mt-1 block">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Berita</label>
                        <input type="file" name="bukti_berita" accept="application/pdf"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <small class="text-xs text-gray-500 mt-1 block">Format: PDF. Maksimal 10MB</small>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end gap-3 pb-4">
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl">
                    <span>Simpan Aset</span>
                </button>
            </div>
        </form>
    </div>
@endsection

@push('page-scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let selectedHierarchy = {};

        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();

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

        function setupEventListeners() {
            document.getElementById('akun')?.addEventListener('change', function() {
                const akunId = this.value;
                selectedHierarchy.akun = getSelectedOption(this);

                if (akunId) {
                    loadKelompoks(akunId);
                    resetDropdowns(['kelompok', 'jenis', 'objek', 'rincian_objek', 'sub_rincian_objek',
                        'sub_sub_rincian_objek'
                    ]);
                } else {
                    resetAllDropdowns();
                }
                updateHierarchyDisplay();
                updateKodeBarang();
            });

            document.getElementById('kelompok')?.addEventListener('change', function() {
                const kelompokId = this.value;
                selectedHierarchy.kelompok = getSelectedOption(this);

                if (kelompokId) {
                    loadJenis(kelompokId);
                    resetDropdowns(['jenis', 'objek', 'rincian_objek', 'sub_rincian_objek',
                        'sub_sub_rincian_objek'
                    ]);
                } else {
                    resetDropdowns(['jenis', 'objek', 'rincian_objek', 'sub_rincian_objek',
                        'sub_sub_rincian_objek'
                    ]);
                }
                updateHierarchyDisplay();
                updateKodeBarang();
            });

            document.getElementById('jenis')?.addEventListener('change', function() {
                const jenisId = this.value;
                selectedHierarchy.jenis = getSelectedOption(this);

                if (jenisId) {
                    loadObjeks(jenisId);
                    resetDropdowns(['objek', 'rincian_objek', 'sub_rincian_objek', 'sub_sub_rincian_objek']);
                } else {
                    resetDropdowns(['objek', 'rincian_objek', 'sub_rincian_objek', 'sub_sub_rincian_objek']);
                }
                updateHierarchyDisplay();
                updateKodeBarang();
            });

            document.getElementById('objek')?.addEventListener('change', function() {
                const objekId = this.value;
                selectedHierarchy.objek = getSelectedOption(this);

                if (objekId) {
                    loadRincianObjeks(objekId);
                    resetDropdowns(['rincian_objek', 'sub_rincian_objek', 'sub_sub_rincian_objek']);
                } else {
                    resetDropdowns(['rincian_objek', 'sub_rincian_objek', 'sub_sub_rincian_objek']);
                }
                updateHierarchyDisplay();
                updateKodeBarang();
            });

            document.getElementById('rincian_objek')?.addEventListener('change', function() {
                const rincianObjekId = this.value;
                selectedHierarchy.rincianObjek = getSelectedOption(this);

                if (rincianObjekId) {
                    loadSubRincianObjeks(rincianObjekId);
                    resetDropdowns(['sub_rincian_objek', 'sub_sub_rincian_objek']);
                } else {
                    resetDropdowns(['sub_rincian_objek', 'sub_sub_rincian_objek']);
                }
                updateHierarchyDisplay();
                updateKodeBarang();
            });

            document.getElementById('sub_rincian_objek')?.addEventListener('change', function() {
                const subRincianObjekId = this.value;
                selectedHierarchy.subRincianObjek = getSelectedOption(this);

                if (selectedHierarchy.subRincianObjek && selectedHierarchy.subRincianObjek.nama) {
                    const namaBidangBarangInput = document.querySelector('input[name="nama_bidang_barang"]');
                    if (namaBidangBarangInput) {
                        namaBidangBarangInput.value = selectedHierarchy.subRincianObjek.nama;
                    }
                } else {
                    const namaBidangBarangInput = document.querySelector('input[name="nama_bidang_barang"]');
                    if (namaBidangBarangInput) {
                        namaBidangBarangInput.value = '';
                    }
                }

                if (subRincianObjekId) {
                    loadSubSubRincianObjeks(subRincianObjekId);
                    resetDropdowns(['sub_sub_rincian_objek']);
                } else {
                    resetDropdowns(['sub_sub_rincian_objek']);
                }
                updateHierarchyDisplay();
                updateKodeBarang();
            });

            document.getElementById('sub_sub_rincian_objek')?.addEventListener('change', function() {
                selectedHierarchy.subSubRincianObjek = getSelectedOption(this);

                if (selectedHierarchy.subSubRincianObjek && selectedHierarchy.subSubRincianObjek.nama) {
                    const namaJenisBarangInput = document.querySelector('input[name="nama_jenis_barang"]');
                    if (namaJenisBarangInput) {
                        namaJenisBarangInput.value = selectedHierarchy.subSubRincianObjek.nama;
                        namaJenisBarangInput.classList.add('auto-filled');
                    }
                } else {
                    const namaJenisBarangInput = document.querySelector('input[name="nama_jenis_barang"]');
                    if (namaJenisBarangInput) {
                        namaJenisBarangInput.value = '';
                        namaJenisBarangInput.classList.remove('auto-filled');
                    }
                }

                updateHierarchyDisplay();
                updateKodeBarang();
            });

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

                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Sedang memproses data aset',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            });

            const keadaanBarangSelect = document.querySelector('select[name="keadaan_barang"]');
            if (keadaanBarangSelect) {
                keadaanBarangSelect.addEventListener('change', function() {
                    handleKeadaanBarangChange(this.value);
                });
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

        function loadKelompoks(akunId) {
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

        function loadJenis(kelompokId) {
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

        function loadObjeks(jenisId) {
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

        function loadRincianObjeks(objekId) {
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

        function loadSubRincianObjeks(rincianObjekId) {
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

        function loadSubSubRincianObjeks(subRincianObjekId) {
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

                    const key = id.replace(/_/g, '').replace('objek', 'Objek');
                    if (selectedHierarchy[key]) {
                        delete selectedHierarchy[key];
                    }

                    if (id === 'sub_rincian_objek') {
                        const namaBidangBarangInput = document.querySelector('input[name="nama_bidang_barang"]');
                        if (namaBidangBarangInput) {
                            namaBidangBarangInput.value = '';
                            namaBidangBarangInput.classList.remove('auto-filled');
                        }
                    }

                    if (id === 'sub_sub_rincian_objek') {
                        const namaJenisBarangInput = document.querySelector('input[name="nama_jenis_barang"]');
                        if (namaJenisBarangInput) {
                            namaJenisBarangInput.value = '';
                            namaJenisBarangInput.classList.remove('auto-filled');
                        }
                    }
                }
            });
        }

        function resetAllDropdowns() {
            resetDropdowns(['kelompok', 'jenis', 'objek', 'rincian_objek', 'sub_rincian_objek', 'sub_sub_rincian_objek']);
            hideKodePreview();
            selectedHierarchy = {};
            updateHierarchyDisplay();

            const namaBidangBarangInput = document.querySelector('input[name="nama_bidang_barang"]');
            const namaJenisBarangInput = document.querySelector('input[name="nama_jenis_barang"]');

            if (namaBidangBarangInput) {
                namaBidangBarangInput.value = '';
                namaBidangBarangInput.classList.remove('auto-filled');
            }

            if (namaJenisBarangInput) {
                namaJenisBarangInput.value = '';
                namaJenisBarangInput.classList.remove('auto-filled');
            }
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
                    document.getElementById('kode-preview').classList.remove('hidden');

                    updateRegisterPreview();
                }
            } else {
                hideKodePreview();
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
            if (preview) preview.classList.add('hidden');
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
                hierarchyDisplay.classList.remove('hidden');
            } else {
                hierarchyDisplay.classList.add('hidden');
            }
        }

        function updateRegisterPreview() {
            const kodeBarang = document.getElementById('kode_barang').value;
            const registerInput = document.getElementById('register');
            const registerInfo = document.getElementById('register_info');

            if (!kodeBarang) {
                registerInput.value = '';
                if (registerInfo) registerInfo.innerHTML = '';
                return;
            }

            if (registerInfo) {
                registerInfo.innerHTML = '<small class="text-blue-600">Mengambil informasi register...</small>';
            }

            fetch('/asets/get-register-info', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        kode_barang: kodeBarang
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const nextRegisterFormatted = data.data.next_register_formatted;
                        registerInput.value = nextRegisterFormatted;

                        if (registerInfo) {
                            registerInfo.innerHTML = `<small class="text-green-600">${data.data.info_message}</small>`;
                        }

                        registerInput.placeholder = `Register berikutnya: ${nextRegisterFormatted}`;
                    } else {
                        console.error('Error:', data.message);
                        if (registerInfo) {
                            registerInfo.innerHTML = `<small class="text-red-600">Error: ${data.message}</small>`;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (registerInfo) {
                        registerInfo.innerHTML =
                            '<small class="text-red-600">Terjadi kesalahan saat mengambil informasi register</small>';
                    }
                });
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
                    kodePreview.classList.remove('hidden', 'from-green-50', 'to-emerald-50', 'border-green-300');
                    kodePreview.classList.add('from-red-50', 'to-rose-50', 'border-red-300');
                }

                showRusakBeratWarning();
                updateRegisterForRusakBerat();

            } else if (keadaanBarang === 'B' || keadaanBarang === 'KB') {
                updateKodeBarang();

                if (kodeBarangDisplay) {
                    kodeBarangDisplay.classList.remove('text-red-700');
                    kodeBarangDisplay.classList.add('text-green-700');
                }

                if (kodePreview) {
                    kodePreview.classList.remove('from-red-50', 'to-rose-50', 'border-red-300');
                    kodePreview.classList.add('from-green-50', 'to-emerald-50', 'border-green-300');
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

        function updateRegisterForRusakBerat() {
            const kodeBarang = '1.5.4.01.01.01.005';
            const registerInput = document.getElementById('register');
            const registerInfo = document.getElementById('register_info');

            if (!registerInput) return;

            if (registerInfo) {
                registerInfo.innerHTML =
                    '<small class="text-blue-600">Mengambil informasi register untuk aset rusak berat...</small>';
            }

            fetch('/asets/get-register-info', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        kode_barang: kodeBarang
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const nextRegisterFormatted = data.data.next_register_formatted;
                        registerInput.value = nextRegisterFormatted;

                        if (registerInfo) {
                            registerInfo.innerHTML =
                                `<small class="text-yellow-600">Aset Rusak Berat - ${data.data.info_message}</small>`;
                        }
                    } else {
                        console.error('Error:', data.message);
                        if (registerInfo) {
                            registerInfo.innerHTML = `<small class="text-red-600">Error: ${data.message}</small>`;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (registerInfo) {
                        registerInfo.innerHTML =
                            '<small class="text-red-600">Terjadi kesalahan saat mengambil informasi register</small>';
                    }
                });
        }

        function goBack() {
            window.history.back();
        }
    </script>
@endpush
