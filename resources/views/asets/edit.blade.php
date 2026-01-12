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
                    <h2 class="text-lg font-semibold text-gray-900">Hierarki Aset (Read-Only)</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{-- 1. Akun --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            1. Akun <span class="text-red-500">*</span>
                        </label>
                        <select id="akun" disabled
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed opacity-75">
                            <option value="">Pilih Akun</option>
                            @foreach ($akuns as $akun)
                                <option value="{{ $akun->id }}" data-kode="{{ $akun->kode }}"
                                    {{ old('akun_id', $selectedValues['akun_id'] ?? '') == $akun->id ? 'selected' : '' }}>
                                    {{ $akun->kode }} - {{ $akun->nama }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="akun_id" value="{{ old('akun_id', $selectedValues['akun_id'] ?? '') }}">
                    </div>

                    {{-- 2. Kelompok --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            2. Kelompok <span class="text-red-500">*</span>
                        </label>
                        <select id="kelompok" disabled
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed opacity-75">
                            <option value="">Pilih Kelompok</option>
                            @foreach ($kelompoks as $kelompok)
                                <option value="{{ $kelompok->id }}" data-kode="{{ $kelompok->kode }}"
                                    {{ old('kelompok_id', $selectedValues['kelompok_id'] ?? '') == $kelompok->id ? 'selected' : '' }}>
                                    {{ $kelompok->kode }} - {{ $kelompok->nama }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="kelompok_id"
                            value="{{ old('kelompok_id', $selectedValues['kelompok_id'] ?? '') }}">
                    </div>

                    {{-- 3. Jenis --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            3. Jenis <span class="text-red-500">*</span>
                        </label>
                        <select id="jenis" disabled
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed opacity-75">
                            <option value="">Pilih Jenis</option>
                            @foreach ($jenis as $j)
                                <option value="{{ $j->id }}" data-kode="{{ $j->kode }}"
                                    {{ old('jenis_id', $selectedValues['jenis_id'] ?? '') == $j->id ? 'selected' : '' }}>
                                    {{ $j->kode }} - {{ $j->nama }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="jenis_id"
                            value="{{ old('jenis_id', $selectedValues['jenis_id'] ?? '') }}">
                    </div>

                    {{-- 4. Objek --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            4. Objek <span class="text-red-500">*</span>
                        </label>
                        <select id="objek" disabled
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed opacity-75">
                            <option value="">Pilih Objek</option>
                            @foreach ($objeks as $objek)
                                <option value="{{ $objek->id }}" data-kode="{{ $objek->kode }}"
                                    {{ old('objek_id', $selectedValues['objek_id'] ?? '') == $objek->id ? 'selected' : '' }}>
                                    {{ $objek->kode }} - {{ $objek->nama }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="objek_id"
                            value="{{ old('objek_id', $selectedValues['objek_id'] ?? '') }}">
                    </div>

                    {{-- 5. Rincian Objek --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            5. Rincian Objek <span class="text-red-500">*</span>
                        </label>
                        <select id="rincian_objek" disabled
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed opacity-75">
                            <option value="">Pilih Rincian Objek</option>
                            @foreach ($rincianObjeks as $rincianObjek)
                                <option value="{{ $rincianObjek->id }}" data-kode="{{ $rincianObjek->kode }}"
                                    {{ old('rincian_objek_id', $selectedValues['rincian_objek_id'] ?? '') == $rincianObjek->id ? 'selected' : '' }}>
                                    {{ $rincianObjek->kode }} - {{ $rincianObjek->nama }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="rincian_objek_id"
                            value="{{ old('rincian_objek_id', $selectedValues['rincian_objek_id'] ?? '') }}">
                    </div>

                    {{-- 6. Sub Rincian Objek --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            6. Sub Rincian Objek <span class="text-red-500">*</span>
                        </label>
                        <select id="sub_rincian_objek" disabled
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed opacity-75">
                            <option value="">Pilih Sub Rincian Objek</option>
                            @foreach ($subRincianObjeks as $subRincianObjek)
                                <option value="{{ $subRincianObjek->id }}" data-kode="{{ $subRincianObjek->kode }}"
                                    {{ old('sub_rincian_objek_id', $selectedValues['sub_rincian_objek_id'] ?? '') == $subRincianObjek->id ? 'selected' : '' }}>
                                    {{ $subRincianObjek->kode }} - {{ $subRincianObjek->nama }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="sub_rincian_objek_id"
                            value="{{ old('sub_rincian_objek_id', $selectedValues['sub_rincian_objek_id'] ?? '') }}">
                    </div>

                    {{-- 7. Sub Sub Rincian Objek --}}
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            7. Sub Sub Rincian Objek <span class="text-red-500">*</span>
                        </label>
                        <select id="sub_sub_rincian_objek" disabled
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed opacity-75">
                            <option value="">Pilih Sub Sub Rincian Objek</option>
                            @foreach ($subSubRincianObjeks as $subSubRincianObjek)
                                <option value="{{ $subSubRincianObjek->id }}" data-kode="{{ $subSubRincianObjek->kode }}"
                                    {{ old('sub_sub_rincian_objek_id', $selectedValues['sub_sub_rincian_objek_id'] ?? '') == $subSubRincianObjek->id ? 'selected' : '' }}>
                                    {{ $subSubRincianObjek->kode }} - {{ $subSubRincianObjek->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="sub_sub_rincian_objek_id"
                            value="{{ old('sub_sub_rincian_objek_id', $selectedValues['sub_sub_rincian_objek_id'] ?? '') }}">
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
                    {{-- Lokasi Barang --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Lokasi Barang
                        </label>
                        <input type="text" name="lokasi_barang"
                            value="{{ old('lokasi_barang', $aset->lokasi_barang) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg
               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Contoh: Gudang A, Lantai 2">
                    </div>

                    {{-- Ruangan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ruangan
                        </label>
                        <select name="ruangan"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg
               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Pilih Ruangan</option>
                            @foreach (config('ruangan') as $ruangan)
                                <option value="{{ $ruangan }}"
                                    {{ old('ruangan', $aset->ruangan) === $ruangan ? 'selected' : '' }}>
                                    {{ $ruangan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Keterangan --}}
                    <div class="md:col-span-2 lg:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan
                        </label>
                        <textarea name="keterangan" rows="3"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg
               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Tambahkan keterangan tambahan jika diperlukan">{{ old('keterangan', $aset->keterangan) }}</textarea>
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
    {{-- Confirm Modal --}}
    <x-notifications.confirm-modal />
@endsection

@push('page-scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ===================================
        // KONSTANTA & KONFIGURASI
        // ===================================
        const isEditMode = true;
        const LOCKED_KODE_BARANG = "{{ old('kode_barang', $aset->kode_barang) }}";
        const KODE_RUSAK_BERAT = '1.5.4.01.01.01.005';

        let selectedHierarchy = {
            akun: @json($selectedHierarchy['akun'] ?? null),
            kelompok: @json($selectedHierarchy['kelompok'] ?? null),
            jenis: @json($selectedHierarchy['jenis'] ?? null),
            objek: @json($selectedHierarchy['objek'] ?? null),
            rincianObjek: @json($selectedHierarchy['rincian_objek'] ?? null),
            subRincianObjek: @json($selectedHierarchy['sub_rincian_objek'] ?? null),
            subSubRincianObjek: @json($selectedHierarchy['sub_sub_rincian_objek'] ?? null)
        };

        const selectedValues = @json($selectedValues ?? []);

        // ===================================
        // INITIALIZATION
        // ===================================
        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();

            // Initial display
            updateHierarchyDisplay();
            updateKodeBarang();

            // Validation errors
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

        // ===================================
        // EVENT LISTENERS SETUP
        // ===================================
        function setupEventListeners() {
            // Form submit
            document.getElementById('assetForm')?.addEventListener('submit', function(e) {
                e.preventDefault();

                const isValid = validateDropdowns();
                if (!isValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Harap lengkapi semua level hierarki aset yang wajib diisi!',
                    });
                    return;
                }

                const modal = Alpine.$data(document.querySelector('[x-data*="confirmModal"]'));
                modal.show({
                    title: 'Update Aset',
                    message: 'Apakah Anda yakin ingin memperbarui data aset ini?',
                    confirmText: 'Ya, Update',
                    cancelText: 'Batal',
                    type: 'warning',
                    onConfirm: () => {
                        e.target.submit();
                    }
                });
            });

            // Keadaan barang change
            const keadaanBarangSelect = document.querySelector('select[name="keadaan_barang"]');
            if (keadaanBarangSelect) {
                keadaanBarangSelect.addEventListener('change', function() {
                    handleKeadaanBarangChange(this.value);
                });

                // Trigger untuk nilai existing
                if (keadaanBarangSelect.value) {
                    setTimeout(() => {
                        handleKeadaanBarangChange(keadaanBarangSelect.value);
                    }, 100);
                }
            }
        }

        // ===================================
        // KODE BARANG MANAGEMENT
        // ===================================
        function updateKodeBarang() {
            const kodeInput = document.getElementById('kode_barang');
            const kodeDisplay = document.getElementById('kode-barang-text');
            const kodePreview = document.getElementById('kode-preview');

            // Gunakan kode barang locked dari database
            if (kodeInput && LOCKED_KODE_BARANG) {
                kodeInput.value = LOCKED_KODE_BARANG;
            }
            if (kodeDisplay && LOCKED_KODE_BARANG) {
                kodeDisplay.textContent = LOCKED_KODE_BARANG;
            }
            if (kodePreview && LOCKED_KODE_BARANG) {
                kodePreview.style.display = 'block';
            }
        }

        function handleKeadaanBarangChange(keadaanBarang) {
            const kodeBarangDisplay = document.getElementById('kode-barang-text');
            const kodeBarangInput = document.getElementById('kode_barang');
            const kodePreview = document.getElementById('kode-preview');

            // ⚠️ EXCEPTION: Jika RB, ubah kode barang
            if (keadaanBarang === 'RB') {
                if (kodeBarangDisplay) {
                    kodeBarangDisplay.textContent = KODE_RUSAK_BERAT;
                    kodeBarangDisplay.classList.remove('text-green-700');
                    kodeBarangDisplay.classList.add('text-red-700');
                }

                if (kodeBarangInput) {
                    kodeBarangInput.value = KODE_RUSAK_BERAT;
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
                return;
            }

            // ⚠️ Jika bukan RB, kembalikan ke kode locked
            if (kodeBarangDisplay) {
                kodeBarangDisplay.textContent = LOCKED_KODE_BARANG;
                kodeBarangDisplay.classList.remove('text-red-700');
                kodeBarangDisplay.classList.add('text-green-700');
            }

            if (kodeBarangInput) {
                kodeBarangInput.value = LOCKED_KODE_BARANG;
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

        // ===================================
        // WARNING MANAGEMENT
        // ===================================
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
                        <p class="text-yellow-800 text-sm mt-1">Aset dengan keadaan "Rusak Berat" akan menggunakan kode barang khusus <strong>${KODE_RUSAK_BERAT}</strong> dan akan diurutkan di bagian paling bawah daftar aset.</p>
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

        // ===================================
        // HIERARCHY DISPLAY
        // ===================================
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

        // ===================================
        // VALIDATION
        // ===================================
        function validateDropdowns() {
            // Semua field hierarki sudah terisi via hidden input
            return true;
        }
    </script>
@endpush
