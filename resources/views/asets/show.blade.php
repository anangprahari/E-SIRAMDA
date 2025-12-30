@extends('layouts.app')

@section('title', 'Detail Aset')

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
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            cursor: pointer;
            border-radius: 0.75rem;
        }

        .asset-image-container:hover .image-overlay {
            opacity: 1;
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-6">

        {{-- Header Card (SAMA DENGAN INDEX) --}}
        <div class="mb-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-6 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    {{-- Left : Breadcrumb --}}
                    <div class="flex items-center gap-4">
                        <x-breadcrumbs.aset current="Detail Aset Tetap & Lainnya" />
                    </div>

                    {{-- Right : Actions --}}
                    <div class="flex flex-wrap items-center gap-2">

                        <a href="{{ route('asets.edit', $aset->id) }}"
                            class="inline-flex items-center h-9 px-4
                            bg-yellow-500 text-white rounded-lg text-sm font-medium
                            hover:bg-yellow-600 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>

                        <a href="{{ route('asets.downloadPdf', $aset->id) }}"
                            class="inline-flex items-center h-9 px-4
                            bg-purple-600 text-white rounded-lg text-sm font-medium
                            hover:bg-purple-700 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            PDF
                        </a>

                        <button onclick="confirmDelete('{{ route('asets.destroy', $aset->id) }}')"
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
        </div>

        <!-- Asset Overview Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden animate-fade-in-up">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-3 py-3">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="4" y="4" width="6" height="6" rx="1"></rect>
                            <rect x="14" y="4" width="6" height="6" rx="1"></rect>
                            <rect x="4" y="14" width="6" height="6" rx="1"></rect>
                            <rect x="14" y="14" width="6" height="6" rx="1"></rect>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Informasi Utama Aset</h3>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="detail-label">Nama Bidang Barang</label>
                        <div class="detail-value">{{ $aset->nama_bidang_barang }}</div>
                    </div>
                    <div>
                        <label class="detail-label">Nama Jenis Barang</label>
                        <div class="detail-value">{{ $aset->nama_jenis_barang }}</div>
                    </div>
                    <div>
                        <label class="detail-label">Kode Barang</label>
                        <div class="detail-value">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-lg font-mono text-sm">
                                {{ $aset->kode_barang }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="detail-label">Register</label>
                        <div class="detail-value">
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-lg font-mono text-sm">
                                {{ $aset->register }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="detail-label">Merk / Type</label>
                        <div class="detail-value">{{ $aset->merk_type ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="detail-label">Bahan</label>
                        <div class="detail-value">{{ $aset->bahan ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Technical Details Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden animate-fade-in-up"
            style="animation-delay: 0.1s">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-3 py-3">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z">
                            </path>
                            <path d="M17 4a2 2 0 0 0 2 2a2 2 0 0 0 -2 2a2 2 0 0 0 -2 -2a2 2 0 0 0 2 -2"></path>
                            <path d="M19 11h2m-1 -1v2"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Spesifikasi Teknis</h3>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="detail-label">No. Sertifikat</label>
                        <div class="detail-value">{{ $aset->no_sertifikat ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="detail-label">No. Plat Kendaraan</label>
                        <div class="detail-value">{{ $aset->no_plat_kendaraan ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="detail-label">No. Pabrik</label>
                        <div class="detail-value">{{ $aset->no_pabrik ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="detail-label">No. Casis</label>
                        <div class="detail-value">{{ $aset->no_casis ?? '-' }}</div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="detail-label">Ukuran Barang / Konstruksi</label>
                        <div class="detail-value">{{ $aset->ukuran_barang_konstruksi ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acquisition & Condition Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden animate-fade-in-up"
            style="animation-delay: 0.2s">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-3 py-3">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9"></circle>
                            <polyline points="12,7 12,12 15,15"></polyline>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Perolehan & Kondisi</h3>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="detail-label">Asal Perolehan</label>
                        <div class="detail-value">{{ $aset->asal_perolehan }}</div>
                    </div>
                    <div>
                        <label class="detail-label">Tahun Perolehan</label>
                        <div class="detail-value">
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-lg font-mono text-sm">
                                {{ $aset->tahun_perolehan }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="detail-label">Keadaan Barang</label>
                        <div class="detail-value">
                            <span
                                class="px-3 py-2 rounded-lg text-sm font-semibold inline-flex items-center space-x-1
                                    {{ $aset->keadaan_barang === 'B'
                                        ? 'bg-green-100 text-green-800'
                                        : ($aset->keadaan_barang === 'KB'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : 'bg-red-100 text-red-800') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <span>{{ $aset->keadaan_barang }}</span>
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="detail-label">Satuan</label>
                        <div class="detail-value">{{ $aset->satuan }}</div>
                    </div>
                    <div>
                        <label class="detail-label">Lokasi Barang</label>
                        <div class="detail-value">{{ $aset->lokasi_barang ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="detail-label">Ruangan</label>
                        <div class="detail-value">{{ $aset->ruangan ?? '-' }}</div>
                    </div>
                    <div class="md:col-span-2 lg:col-span-3">
                        <label class="detail-label">Keterangan</label>
                        <div class="detail-value">{{ $aset->keterangan ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Information Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden animate-fade-in-up"
            style="animation-delay: 0.3s">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-3 py-3">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12">
                            </path>
                            <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Informasi Finansial</h3>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="detail-label">Jumlah Barang</label>
                        <div class="detail-value">
                            <span class="font-mono">{{ $aset->jumlah_barang }}</span>
                            <span class="text-gray-500 ml-1">{{ $aset->satuan }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="detail-label">Harga Satuan</label>
                        <div class="detail-value font-mono text-blue-600">{{ $aset->formatted_harga }}</div>
                    </div>
                    <div>
                        <label class="detail-label">Total Harga</label>
                        <div class="detail-value font-mono text-green-600 font-bold">
                            {{ $aset->formatted_total_harga }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documentation Card (Combined: Photo + Documents) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden animate-fade-in-up"
            style="animation-delay: 0.4s">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-3 py-3">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                            <rect x="4" y="4" width="16" height="12" rx="2"></rect>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Dokumentasi Aset</h3>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- Asset Image Section -->
                    @if ($aset->bukti_barang_url)
                        <div class="space-y-3">
                            <div class="asset-image-container relative overflow-hidden rounded-xl border border-gray-200">
                                <img src="{{ $aset->bukti_barang_url }}" alt="Foto {{ $aset->nama_jenis_barang }}"
                                    class="w-full h-[300px] object-cover cursor-pointer transition-transform duration-300 hover:scale-105"
                                    onclick="showImageModal('{{ $aset->bukti_barang_url }}', '{{ $aset->nama_jenis_barang }}')">

                                <div class="image-overlay">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 text-white mx-auto mb-2" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <circle cx="10" cy="10" r="7"></circle>
                                            <line x1="21" y1="21" x2="15" y2="15">
                                            </line>
                                        </svg>
                                        <div class="text-white font-semibold">Klik untuk memperbesar</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div
                            class="flex items-center justify-center h-[300px] bg-gray-100 rounded-xl border border-gray-200">
                            <div class="text-center text-gray-400">
                                <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <rect x="4" y="4" width="16" height="12" rx="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <polyline points="21,15 16,10 5,21"></polyline>
                                </svg>
                                <p class="font-medium">Foto tidak tersedia</p>
                            </div>
                        </div>
                    @endif

                    <!-- Documents Section -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Dokumen Terkait</h4>

                        <!-- Bukti Barang -->
                        <div
                            class="flex items-center justify-between p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <rect x="4" y="4" width="16" height="12" rx="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21,15 16,10 5,21"></polyline>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">Foto Barang</div>
                                    <div class="text-sm text-gray-500">Dokumentasi visual aset</div>
                                </div>
                            </div>
                            <div>
                                @if ($aset->bukti_barang_url)
                                    <a href="{{ $aset->bukti_barang_url }}" target="_blank"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="2"></circle>
                                            <path
                                                d="M22 12c-2.667 4 -6 6 -10 6s-7.333 -2 -10 -6c2.667 -4 6 -6 10 -6s7.333 2 10 6">
                                            </path>
                                        </svg>
                                        <span>Lihat</span>
                                    </a>
                                @else
                                    <span class="px-3 py-1 bg-gray-200 text-gray-600 rounded-lg text-sm">Tidak
                                        ada</span>
                                @endif
                            </div>
                        </div>

                        <!-- Bukti Berita -->
                        <div
                            class="flex items-center justify-between p-4 rounded-lg border border-gray-200 hover:border-red-300 hover:bg-red-50 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-red-100 text-red-600 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M16 6h3a1 1 0 0 1 1 1v11a2 2 0 0 1 -4 0v-13a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1v12a3 3 0 0 0 3 3h11">
                                        </path>
                                        <line x1="8" y1="8" x2="12" y2="8"></line>
                                        <line x1="8" y1="12" x2="12" y2="12"></line>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">Dokumen Berita Acara</div>
                                    <div class="text-sm text-gray-500">File PDF dokumen pendukung</div>
                                </div>
                            </div>
                            <div>
                                @if ($aset->bukti_berita_url)
                                    <a href="{{ $aset->bukti_berita_url }}" target="_blank"
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 3v12"></path>
                                            <path d="M8 11l4 4 4-4"></path>
                                            <path d="M8 4l4 4 4-4"></path>
                                        </svg>
                                        <span>Download</span>
                                    </a>
                                @else
                                    <span class="px-3 py-1 bg-gray-200 text-gray-600 rounded-lg text-sm">Tidak
                                        ada</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4"
        onclick="closeImageModal(event)">
        <div class="relative max-w-5xl w-full bg-white rounded-xl shadow-2xl overflow-hidden"
            onclick="event.stopPropagation()">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gray-50">
                <h5 id="imageModalLabel" class="text-lg font-semibold text-gray-900">Foto Aset</h5>
                <button onclick="closeImageModal()"
                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-200 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-0 bg-gray-900">
                <img id="modalImage" src="" alt="Asset Image" class="w-full max-h-[70vh] object-contain">
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end space-x-3 p-4 border-t border-gray-200 bg-gray-50">
                <button onclick="closeImageModal()"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Tutup
                </button>
                <a id="downloadImageBtn" href="" download
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 3v12m0 0l-4-4m4 4l4-4" />
                        <rect x="4" y="17" width="16" height="4" rx="2" />
                    </svg>
                    <span>Download</span>
                </a>
            </div>
        </div>
    </div>
    <!-- Delete Form -->
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection
@push('page-scripts')
    <script>
        // Image Modal Functions
        function showImageModal(imageUrl, assetName) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalLabel = document.getElementById('imageModalLabel');
            const downloadBtn = document.getElementById('downloadImageBtn');
            modalImage.src = imageUrl;
            modalLabel.textContent = 'Foto ' + assetName;
            downloadBtn.href = imageUrl;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal(event) {
            if (!event || event.target.id === 'imageModal' || event.currentTarget.tagName === 'BUTTON') {
                const modal = document.getElementById('imageModal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }
        }

        // Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });

        // Delete Confirmation
        function confirmDelete(url) {
            if (confirm(
                    '⚠️ Apakah Anda yakin ingin menghapus aset ini?\n\nTindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait aset ini.'
                )) {
                const form = document.getElementById('delete-form');
                if (form) {
                    form.action = url;
                    form.submit();
                }
            }
        }

        // Animate cards on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -100px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '0';
                        entry.target.style.transform = 'translateY(20px)';

                        setTimeout(() => {
                            entry.target.style.transition =
                                'opacity 0.6s ease, transform 0.6s ease';
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }, 50);

                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.animate-fade-in-up').forEach(card => {
                observer.observe(card);
            });
        });
    </script>
@endpush
