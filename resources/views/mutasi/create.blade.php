@extends('layouts.app')
@section('title', 'Tambah Mutasi')
@section('content')
<div class="px-6 py-5 max-w-7xl mx-auto">

    {{-- Header Card --}}
    <div class="mb-5">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-4">
            <div class="flex items-center">
                <x-breadcrumbs.mutasi :items="[
                    ['label' => 'Daftar Mutasi', 'url' => route('mutasi.index')],
                    ['label' => 'Buat Mutasi Baru'],
                ]" />
            </div>
        </div>
    </div>

    {{-- Alert --}}
    @if (session('error'))
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white rounded-xl shadow border border-gray-200 p-6">
        <form action="{{ route('mutasi.store') }}" method="POST" enctype="multipart/form-data" id="formMutasi">
            @csrf

            {{-- SECTION 1 : Informasi Surat --}}
            <div class="mb-6">
                <div class="mb-3">
                    <h3 class="text-base font-semibold text-gray-800">
                        Informasi Surat
                    </h3>
                    <p class="text-xs text-gray-500">
                        Data resmi dokumen mutasi aset
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">
                            Nomor Surat <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}"
                            class="w-full h-9 rounded-lg border-gray-300 text-sm
                            focus:ring-blue-500 focus:border-blue-500"
                            required>
                        @error('nomor_surat')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-400 mt-1">Contoh: 001/MUT/XII/2024</p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">
                            Tanggal Mutasi <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_mutasi"
                            value="{{ old('tanggal_mutasi', date('Y-m-d')) }}"
                            max="{{ date('Y-m-d') }}"
                            class="w-full h-9 rounded-lg border-gray-300 text-sm
                            focus:ring-blue-500 focus:border-blue-500"
                            required>
                        @error('tanggal_mutasi')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- SECTION 2 : Pemilihan Aset --}}
            <div class="mb-6">
                <div class="mb-3">
                    <h3 class="text-base font-semibold text-gray-800">
                        Pemilihan Aset
                    </h3>
                    <p class="text-xs text-gray-500">
                        Pilih aset yang akan dipindahkan
                    </p>
                </div>

                <label class="block text-xs font-medium text-gray-600 mb-1">
                    Aset <span class="text-red-500">*</span>
                </label>
                <select id="aset_id" name="aset_id"
                    class="w-full h-9 rounded-lg border-gray-300 text-sm
                    focus:ring-blue-500 focus:border-blue-500"
                    required>
                    <option value="">-- Pilih Aset --</option>
                    @foreach ($assets as $item)
                        <option value="{{ $item->id }}"
                            data-ruangan="{{ $item->ruangan }}"
                            data-lokasi="{{ $item->lokasi_barang ?? '-' }}"
                            data-register="{{ $item->no_register ?? ($item->register ?? '-') }}"
                            {{ old('aset_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->kode_barang }} — {{ $item->nama_jenis_barang }}
                            (Reg {{ $item->no_register ?? ($item->register ?? '-') }})
                        </option>
                    @endforeach
                </select>
                @error('aset_id')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror

                {{-- Info Aset --}}
                <div id="infoAset"
                    class="hidden mt-3 rounded-lg bg-blue-50 border border-blue-200 p-4 text-sm">
                    <div class="flex flex-col md:flex-row md:justify-between gap-2">
                        <div>
                            <span class="font-medium text-blue-800">Ruangan Saat Ini:</span>
                            <span id="textRuanganAsal"
                                class="ml-2 inline-flex px-2 py-0.5 rounded bg-blue-600 text-white text-xs"></span>
                        </div>
                        <div>
                            <span class="font-medium text-blue-800">Lokasi:</span>
                            <span id="textLokasiAsal" class="ml-2 text-gray-700"></span>
                        </div>
                    </div>
                    <p id="textRegister" class="mt-2 text-xs text-gray-600"></p>
                </div>
            </div>

            {{-- SECTION 3 : Tujuan --}}
            <div class="mb-6">
                <div class="mb-3">
                    <h3 class="text-base font-semibold text-gray-800">
                        Tujuan Perpindahan
                    </h3>
                    <p class="text-xs text-gray-500">
                        Tentukan ruangan dan lokasi tujuan
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">
                            Ruangan Tujuan <span class="text-red-500">*</span>
                        </label>
                        <select name="ruangan_tujuan"
                            class="w-full h-9 rounded-lg border-gray-300 text-sm
                            focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="">-- Pilih Ruangan --</option>
                            @foreach ($ruanganOptions as $ruangan)
                                <option value="{{ $ruangan }}"
                                    @selected(old('ruangan_tujuan') == $ruangan)>
                                    {{ $ruangan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">
                            Lokasi Tujuan
                        </label>
                        <input type="text" name="lokasi_tujuan"
                            value="{{ old('lokasi_tujuan') }}"
                            placeholder="Contoh: Lantai 2, Gedung A"
                            class="w-full h-9 rounded-lg border-gray-300 text-sm
                            focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            {{-- SECTION 4 : Dokumen --}}
            <div class="mb-6">
                <div class="mb-3">
                    <h3 class="text-base font-semibold text-gray-800">
                        Dokumen & Keterangan
                    </h3>
                    <p class="text-xs text-gray-500">
                        Upload dokumen resmi pendukung mutasi
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">
                            Berita Acara (PDF) <span class="text-red-500">*</span>
                        </label>
                        <input type="file" name="berita_acara" accept=".pdf"
                            class="w-full text-sm
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-lg file:border-0
                            file:bg-blue-600 file:text-white
                            hover:file:bg-blue-700"
                            required>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">
                            Keterangan
                        </label>
                        <textarea name="keterangan" rows="3"
                            class="w-full rounded-lg border-gray-300 text-sm
                            focus:ring-blue-500 focus:border-blue-500">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ACTION --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('mutasi.index') }}"
                    class="h-9 px-5 rounded-lg border border-gray-300
                    text-gray-700 text-sm flex items-center
                    hover:bg-gray-100 transition">
                    Batal
                </a>
                <button type="submit"
                    class="h-9 px-6 rounded-lg bg-blue-600
                    hover:bg-blue-700 text-white
                    text-sm font-medium transition">
                    Simpan Mutasi
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
