@extends('layouts.app')

@section('title', 'Detail Aset Lancar')

@push('page-styles')
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease forwards;
        }

        .detail-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            margin-bottom: 0.5rem;
        }

        .detail-value {
            font-size: 1rem;
            color: #111827;
            font-weight: 500;
            font-family: ui-monospace, monospace;
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-6 space-y-6">

        {{-- HEADER CARD (BREADCRUMB) --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-4">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                {{-- Breadcrumb --}}
                <x-breadcrumbs.asetlancar current="Detail Aset Lancar" />

                {{-- Actions --}}
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('aset-lancars.edit', $asetLancar->id) }}"
                        class="inline-flex items-center h-9 px-4
                            bg-yellow-500 text-white rounded-lg text-sm font-medium
                            hover:bg-yellow-600 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>

                    <button onclick="confirmDelete('{{ route('aset-lancars.destroy', $asetLancar->id) }}')"
                        class="inline-flex items-center h-9 px-4
                            bg-red-600 text-white rounded-lg text-sm font-medium
                            hover:bg-red-700 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus
                    </button>
                </div>
            </div>
        </div>

        {{-- INFORMASI UTAMA --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden animate-fade-in-up">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3">
                <h3 class="text-lg font-semibold text-white">Informasi Utama Aset Lancar</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label class="detail-label">Kode Rekening</label>
                    <div class="detail-value">{{ $asetLancar->rekeningUraian->kode_rekening }}</div>
                </div>
                <div>
                    <label class="detail-label">Uraian Rekening</label>
                    <div class="detail-value">{{ $asetLancar->rekeningUraian->uraian }}</div>
                </div>
                <div>
                    <label class="detail-label">Nama Kegiatan</label>
                    <div class="detail-value">{{ $asetLancar->nama_kegiatan }}</div>
                </div>
                <div>
                    <label class="detail-label">Jenis Barang</label>
                    <div class="detail-value">{{ $asetLancar->uraian_jenis_barang ?? '-' }}</div>
                </div>
                @if ($asetLancar->uraian_kegiatan)
                    <div class="lg:col-span-2">
                        <label class="detail-label">Uraian Kegiatan</label>
                        <div class="detail-value">{{ $asetLancar->uraian_kegiatan }}</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- FORMULA PERHITUNGAN --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden animate-fade-in-up">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3">
                <h3 class="text-lg font-semibold text-white">Formula Perhitungan</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="detail-label">Saldo Awal</label>
                    <div class="detail-value">
                        {{ number_format($asetLancar->saldo_awal_unit) }} unit × Rp
                        {{ number_format($asetLancar->saldo_awal_harga_satuan, 0, ',', '.') }}
                    </div>
                </div>
                <div>
                    <label class="detail-label">Mutasi Tambah</label>
                    <div class="detail-value">
                        {{ number_format($asetLancar->mutasi_tambah_unit) }} unit × Rp
                        {{ number_format($asetLancar->mutasi_tambah_harga_satuan, 0, ',', '.') }}
                    </div>
                </div>
                <div>
                    <label class="detail-label">Saldo Akhir Unit</label>
                    <div class="detail-value">
                        {{ number_format($asetLancar->saldo_akhir_unit) }} unit
                    </div>
                </div>
                <div>
                    <label class="detail-label">Saldo Akhir Total</label>
                    <div class="detail-value text-blue-600 font-bold">
                        Rp {{ number_format($asetLancar->saldo_akhir_total, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- DELETE FORM --}}
    <form id="delete-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    {{-- Confirm Modal --}}
    <x-notifications.confirm-modal />
@endsection

@push('page-scripts')
    <script>
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
