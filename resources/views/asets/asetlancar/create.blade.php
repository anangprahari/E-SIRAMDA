@extends('layouts.app')

@section('title', 'Tambah Aset Lancar')

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
                    <x-breadcrumbs.asetlancar current="Tambah Aset Lancar" />
                </div>
            </div>
        </div>
        @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    let errorMessages = '';
                    @foreach ($errors->all() as $error)
                        errorMessages += '{{ $error }}\n';
                    @endforeach
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: errorMessages,
                    });
                });
            </script>
        @endif

        <!-- Form Container -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6 md:p-8">
            <form id="asetLancarForm" action="{{ route('aset-lancars.store') }}" method="POST">
                @csrf

                <!-- Rekening Section -->
                <div class="bg-gray-50 rounded-xl p-6 mb-6 border border-gray-200">
                    <h4 class="text-lg font-semibold text-blue-600 mb-4 pb-2 border-b-2 border-blue-600 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        Informasi Rekening
                    </h4>
                    <div class="grid grid-cols-1">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor Rekening & Uraian <span class="text-red-600 font-bold">*</span>
                            </label>
                            <select
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('rekening_uraian_id') @enderror"
                                id="rekening_uraian_id" name="rekening_uraian_id" required>
                                <option value="">Pilih Nomor Rekening & Uraian</option>
                                @foreach ($rekeningUraians as $rekening)
                                    <option value="{{ $rekening->id }}"
                                        {{ old('rekening_uraian_id') == $rekening->id ? 'selected' : '' }}
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
                </div>

                <!-- Kegiatan Section -->
                <div class="bg-gray-50 rounded-xl p-6 mb-6 border border-gray-200">
                    <h4 class="text-lg font-semibold text-blue-600 mb-4 pb-2 border-b-2 border-blue-600 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                            </path>
                        </svg>
                        Informasi Kegiatan
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kegiatan</label>
                            <input type="text"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('nama_kegiatan') @enderror"
                                id="nama_kegiatan" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}"
                                placeholder="Masukkan nama kegiatan">
                            @error('nama_kegiatan')
                                <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Uraian Jenis/Barang</label>
                            <input type="text"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('uraian_jenis_barang') @enderror"
                                id="uraian_jenis_barang" name="uraian_jenis_barang"
                                value="{{ old('uraian_jenis_barang') }}" placeholder="Masukkan uraian jenis/barang">
                            @error('uraian_jenis_barang')
                                <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Uraian Kegiatan</label>
                        <textarea
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('uraian_kegiatan') @enderror"
                            id="uraian_kegiatan" name="uraian_kegiatan" rows="4" placeholder="Masukkan uraian detail kegiatan">{{ old('uraian_kegiatan') }}</textarea>
                        @error('uraian_kegiatan')
                            <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Saldo Awal Section -->
                <div
                    class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-6 mb-6 border-2 border-cyan-500 transition-all duration-300 hover:shadow-lg">
                    <h4 class="text-lg font-semibold text-blue-600 mb-4 pb-2 border-b-2 border-blue-600 flex items-center">
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
                                id="saldo_awal_unit" name="saldo_awal_unit" value="{{ old('saldo_awal_unit', 0) }}"
                                min="0" step="1" required>
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
                                    value="{{ old('saldo_awal_harga_satuan', 0) }}" min="0" step="1" required>
                            </div>
                            @error('saldo_awal_harga_satuan')
                                <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nilai Total <small class="text-green-600">(Otomatis)</small>
                            </label>
                            <div class="flex">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-lg border-2 border-r-0 border-cyan-500 bg-blue-50 text-gray-700 font-semibold">Rp</span>
                                <input type="text"
                                    class="w-full px-4 py-3 border-2 border-cyan-500 rounded-r-lg bg-blue-50 font-semibold text-gray-700"
                                    id="saldo_awal_total_display" readonly placeholder="0">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mutasi Section -->
                <div
                    class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-6 mb-6 border-2 border-cyan-500 transition-all duration-300 hover:shadow-lg">
                    <h4 class="text-lg font-semibold text-blue-600 mb-4 pb-2 border-b-2 border-blue-600 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        Mutasi
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
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Unit</label>
                                <input type="number"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('mutasi_tambah_unit') @enderror"
                                    id="mutasi_tambah_unit" name="mutasi_tambah_unit"
                                    value="{{ old('mutasi_tambah_unit', 0) }}" min="0" step="1">
                                @error('mutasi_tambah_unit')
                                    <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Satuan</label>
                                <div class="flex">
                                    <span
                                        class="inline-flex items-center px-3 rounded-l-lg border-2 border-r-0 border-gray-300 bg-gray-100 text-gray-700 font-semibold">Rp</span>
                                    <input type="number"
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('mutasi_tambah_harga_satuan') @enderror"
                                        id="mutasi_tambah_harga_satuan" name="mutasi_tambah_harga_satuan"
                                        value="{{ old('mutasi_tambah_harga_satuan', 0) }}" min="0"
                                        step="1">
                                </div>
                                @error('mutasi_tambah_harga_satuan')
                                    <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nilai Total <small class="text-green-600">(Otomatis)</small>
                                </label>
                                <div class="flex">
                                    <span
                                        class="inline-flex items-center px-3 rounded-l-lg border-2 border-r-0 border-cyan-500 bg-blue-50 text-gray-700 font-semibold">Rp</span>
                                    <input type="text"
                                        class="w-full px-4 py-3 border-2 border-cyan-500 rounded-r-lg bg-blue-50 font-semibold text-gray-700"
                                        id="mutasi_tambah_nilai_total_display" readonly placeholder="0">
                                </div>
                            </div>
                        </div>
                    </div>

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
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Unit</label>
                                <input type="number"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('mutasi_kurang_unit') @enderror"
                                    id="mutasi_kurang_unit" name="mutasi_kurang_unit"
                                    value="{{ old('mutasi_kurang_unit', 0) }}" min="0" step="1">
                                @error('mutasi_kurang_unit')
                                    <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nilai Total</label>
                                <div class="flex">
                                    <span
                                        class="inline-flex items-center px-3 rounded-l-lg border-2 border-r-0 border-gray-300 bg-gray-100 text-gray-700 font-semibold">Rp</span>
                                    <input type="number"
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 @error('mutasi_kurang_nilai_total') @enderror"
                                        id="mutasi_kurang_nilai_total" name="mutasi_kurang_nilai_total"
                                        value="{{ old('mutasi_kurang_nilai_total', 0) }}" min="0" step="1">
                                </div>
                                @error('mutasi_kurang_nilai_total')
                                    <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Saldo Akhir Section -->
                <div
                    class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-6 mb-6 border-2 border-cyan-500 transition-all duration-300 hover:shadow-lg">
                    <h4 class="text-lg font-semibold text-blue-600 mb-4 pb-2 border-b-2 border-blue-600 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>
                        Saldo Akhir
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Unit Barang <small class="text-amber-600">(Otomatis)</small>
                            </label>
                            <input type="text"
                                class="w-full px-4 py-3 border-2 border-cyan-500 rounded-lg bg-blue-50 font-semibold text-amber-600"
                                id="saldo_akhir_unit_display" readonly placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nilai Total <small class="text-amber-600">(Otomatis)</small>
                            </label>
                            <div class="flex">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-lg border-2 border-r-0 border-cyan-500 bg-blue-50 text-gray-700 font-semibold">Rp</span>
                                <input type="text"
                                    class="w-full px-4 py-3 border-2 border-cyan-500 rounded-r-lg bg-blue-50 font-semibold text-amber-600"
                                    id="saldo_akhir_total_display" readonly placeholder="0">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6">
                    <button type="reset"
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        Reset
                    </button>
                    <button type="submit"
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('page-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.32/sweetalert2.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Format number function
            function formatNumber(num) {
                if (isNaN(num) || num === null || num === undefined) return '0';
                return Math.round(num).toLocaleString('id-ID');
            }

            // Update calculations
            function updateCalculations() {
                try {
                    const saldoAwalUnit = parseFloat(document.getElementById('saldo_awal_unit').value) || 0;
                    const saldoAwalHargaSatuan = parseFloat(document.getElementById('saldo_awal_harga_satuan')
                        .value) || 0;
                    const mutasiTambahUnit = parseFloat(document.getElementById('mutasi_tambah_unit').value) || 0;
                    const mutasiTambahHargaSatuan = parseFloat(document.getElementById('mutasi_tambah_harga_satuan')
                        .value) || 0;
                    const mutasiKurangUnit = parseFloat(document.getElementById('mutasi_kurang_unit').value) || 0;
                    const mutasiKurangNilaiTotal = parseFloat(document.getElementById('mutasi_kurang_nilai_total')
                        .value) || 0;

                    // Determine harga satuan
                    let hargaSatuan = 0;
                    if (saldoAwalHargaSatuan > 0) {
                        hargaSatuan = saldoAwalHargaSatuan;
                    } else if (mutasiTambahHargaSatuan > 0) {
                        hargaSatuan = mutasiTambahHargaSatuan;
                    }

                    // Calculate Saldo Awal Total
                    const saldoAwalTotal = saldoAwalUnit * saldoAwalHargaSatuan;
                    const saldoAwalTotalField = document.getElementById('saldo_awal_total_display');
                    if (saldoAwalTotalField) {
                        saldoAwalTotalField.value = formatNumber(saldoAwalTotal);
                    }

                    // Calculate Mutasi Tambah Nilai Total
                    const mutasiTambahNilaiTotal = mutasiTambahUnit * mutasiTambahHargaSatuan;
                    const mutasiTambahField = document.getElementById('mutasi_tambah_nilai_total_display');
                    if (mutasiTambahField) {
                        mutasiTambahField.value = formatNumber(mutasiTambahNilaiTotal);
                    }

                    // Calculate Saldo Akhir Unit
                    const saldoAkhirUnit = saldoAwalUnit + mutasiTambahUnit - mutasiKurangUnit;
                    const saldoAkhirUnitField = document.getElementById('saldo_akhir_unit_display');
                    if (saldoAkhirUnitField) {
                        saldoAkhirUnitField.value = formatNumber(saldoAkhirUnit);
                    }

                    // Calculate Saldo Akhir Total
                    let saldoAkhirTotal = 0;
                    if (saldoAkhirUnit > 0 && hargaSatuan > 0) {
                        saldoAkhirTotal = saldoAkhirUnit * hargaSatuan;
                    }
                    const saldoAkhirTotalField = document.getElementById('saldo_akhir_total_display');
                    if (saldoAkhirTotalField) {
                        saldoAkhirTotalField.value = formatNumber(saldoAkhirTotal);
                    }

                    // Auto-fill mutasi kurang nilai total
                    const mutasiKurangNilaiTotalField = document.getElementById('mutasi_kurang_nilai_total');
                    if (mutasiKurangUnit > 0 && hargaSatuan > 0) {
                        const autoKurangTotal = mutasiKurangUnit * hargaSatuan;
                        // Update otomatis berdasarkan harga satuan yang benar
                        mutasiKurangNilaiTotalField.value = autoKurangTotal;
                    }
                } catch (error) {
                    console.error('Error in calculation:', error);
                }
            }

            // Add event listeners
            function addCalculationListeners() {
                const inputFields = [
                    'saldo_awal_unit',
                    'saldo_awal_harga_satuan',
                    'mutasi_tambah_unit',
                    'mutasi_tambah_harga_satuan',
                    'mutasi_kurang_unit',
                    'mutasi_kurang_nilai_total'
                ];

                inputFields.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        field.addEventListener('input', updateCalculations);
                        field.addEventListener('change', updateCalculations);
                    }
                });
            }

            // Form submission handler
            const form = document.getElementById('asetLancarForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const saldoAwalUnit = parseFloat(document.getElementById('saldo_awal_unit').value) || 0;
                    const saldoAwalHargaSatuan = parseFloat(document.getElementById(
                        'saldo_awal_harga_satuan').value) || 0;
                    const mutasiTambahUnit = parseFloat(document.getElementById('mutasi_tambah_unit')
                        .value) || 0;
                    const mutasiTambahHargaSatuan = parseFloat(document.getElementById(
                        'mutasi_tambah_harga_satuan').value) || 0;
                    const mutasiKurangUnit = parseFloat(document.getElementById('mutasi_kurang_unit')
                        .value) || 0;

                    // Validation
                    if (saldoAwalHargaSatuan == 0 && mutasiTambahHargaSatuan == 0) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Harga satuan harus diisi, baik di Saldo Awal atau Mutasi Tambah.'
                        });
                        return;
                    }

                    if (saldoAwalUnit > 0 && saldoAwalHargaSatuan == 0) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Jika ada unit saldo awal, harga satuan saldo awal harus diisi.'
                        });
                        return;
                    }

                    if (mutasiTambahUnit > 0 && mutasiTambahHargaSatuan == 0) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Jika ada unit mutasi tambah, harga satuan mutasi tambah harus diisi.'
                        });
                        return;
                    }

                    const totalUnit = saldoAwalUnit + mutasiTambahUnit - mutasiKurangUnit;
                    if (totalUnit < 0) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: 'Saldo Akhir Unit tidak boleh negatif. Silakan periksa kembali data mutasi Anda.'
                        });
                        return;
                    }

                    // Show loading
                    Swal.fire({
                        title: 'Menyimpan...',
                        text: 'Sedang memproses data aset lancar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                });
            }

            // Reset form handler
            const resetButton = document.querySelector('button[type="reset"]');
            if (resetButton) {
                resetButton.addEventListener('click', function() {
                    setTimeout(updateCalculations, 200);
                });
            }

            // Initialize
            addCalculationListeners();
            updateCalculations();
        });
    </script>
@endpush
