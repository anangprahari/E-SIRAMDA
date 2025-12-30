<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Aset - {{ $aset->register }}</title>
    <style>
        @page {
            margin: 2cm 1.5cm 3cm 1.5cm;
            font-family: 'DejaVu Sans', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #2d3748;
            background-color: #ffffff;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 0;
        }

        /* Header Styles */
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding: 20px 0 15px 0;
            border-bottom: 3px solid #2b6cb0;
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
        }

        .header h1 {
            font-size: 20px;
            font-weight: bold;
            color: #1a202c;
            margin-bottom: 8px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .header .subtitle {
            font-size: 14px;
            color: #2b6cb0;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .header .generated-info {
            font-size: 9px;
            color: #718096;
            font-style: italic;
        }

        /* Section Styles */
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #1a202c;
            margin-bottom: 12px;
            padding: 8px 12px;
            background-color: #f1f5f9;
            border-left: 4px solid #3182ce;
            border-radius: 0 4px 4px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Table Styles */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .info-table tr {
            border-bottom: 1px solid #e2e8f0;
        }

        .info-table tr:last-child {
            border-bottom: none;
        }

        .info-label {
            width: 35%;
            padding: 8px 12px 8px 0;
            font-weight: 600;
            color: #4a5568;
            vertical-align: top;
            background-color: #f9fafb;
        }

        .info-value {
            width: 65%;
            padding: 8px 0 8px 12px;
            color: #2d3748;
            vertical-align: top;
            word-wrap: break-word;
        }

        /* Hierarchy Section */
        .hierarchy-section {
            background: linear-gradient(135deg, #ebf8ff 0%, #bee3f8 100%);
            padding: 18px;
            margin-bottom: 25px;
            border-radius: 8px;
            border: 1px solid #90cdf4;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .hierarchy-section .section-title {
            background-color: #3182ce;
            color: white;
            margin: -18px -18px 15px -18px;
            border-radius: 8px 8px 0 0;
            border-left: none;
        }

        /* Financial Section */
        .financial-section {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            padding: 18px;
            margin: 20px 0;
            border-radius: 8px;
            border: 1px solid #f59e0b;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .financial-section .section-title {
            background-color: #d97706;
            color: white;
            margin: -18px -18px 15px -18px;
            border-radius: 8px 8px 0 0;
            border-left: none;
        }

        .financial-highlight {
            background-color: #fbbf24;
            color: #92400e;
            padding: 6px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
        }

        /* Image Section */
        .image-section {
            text-align: center;
            margin: 25px 0;
            page-break-inside: avoid;
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .asset-image {
            max-width: 100%;
            max-height: 350px;
            border: 3px solid #cbd5e0;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .no-image {
            background-color: #f7fafc;
            border: 2px dashed #a0aec0;
            padding: 40px 20px;
            text-align: center;
            color: #718096;
            border-radius: 8px;
            font-style: italic;
        }

        .image-caption {
            margin-top: 8px;
            font-size: 9px;
            color: #718096;
            font-style: italic;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-baik {
            background-color: #c6f6d5;
            color: #22543d;
            border: 1px solid #68d391;
        }

        .status-kurang-baik {
            background-color: #fef5e7;
            color: #975a16;
            border: 1px solid #f6ad55;
        }

        .status-rusak-berat {
            background-color: #fed7d7;
            color: #742a2a;
            border: 1px solid #fc8181;
        }

        /* Document Info Section */
        .document-info {
            margin-top: 25px;
            padding: 15px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 8px;
            border: 1px solid #0ea5e9;
        }

        .document-info .info-title {
            font-weight: bold;
            color: #0c4a6e;
            margin-bottom: 8px;
            font-size: 12px;
        }

        .document-info .info-text {
            font-size: 10px;
            color: #0369a1;
            line-height: 1.4;
        }

        .document-info .warning-text {
            color: #dc2626;
            font-weight: 600;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 1cm;
            left: 1.5cm;
            right: 1.5cm;
            text-align: center;
            font-size: 9px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            background-color: rgba(255, 255, 255, 0.9);
        }

        .footer .footer-line {
            margin: 2px 0;
        }

        /* Spacing Utilities */
        .mb-small {
            margin-bottom: 10px;
        }

        .mb-medium {
            margin-bottom: 15px;
        }

        .mb-large {
            margin-bottom: 20px;
        }

        .mt-small {
            margin-top: 10px;
        }

        .mt-medium {
            margin-top: 15px;
        }

        .mt-large {
            margin-top: 20px;
        }

        /* Text Utilities */
        .text-bold {
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-small {
            font-size: 10px;
        }

        .text-large {
            font-size: 13px;
        }

        /* Print Optimizations */
        @media print {
            .container {
                margin: 0;
                padding: 0;
            }

            .section {
                page-break-inside: avoid;
            }

            .image-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Laporan Detail Aset</h1>
            <p class="subtitle">Register: {{ $aset->register }}</p>
            <p class="generated-info">Digenerate pada: {{ $generatedAt }}</p>
        </div>

        <!-- Hierarki Klasifikasi -->
        <div class="hierarchy-section section">
            <div class="section-title">Klasifikasi Hierarki Aset</div>
            <table class="info-table">
                <tr>
                    <td class="info-label">Akun:</td>
                    <td class="info-value">{{ $hierarchy['akun']->kode }} - {{ $hierarchy['akun']->nama }}</td>
                </tr>
                <tr>
                    <td class="info-label">Kelompok:</td>
                    <td class="info-value">{{ $hierarchy['kelompok']->kode }} - {{ $hierarchy['kelompok']->nama }}</td>
                </tr>
                <tr>
                    <td class="info-label">Jenis:</td>
                    <td class="info-value">{{ $hierarchy['jenis']->kode }} - {{ $hierarchy['jenis']->nama }}</td>
                </tr>
                <tr>
                    <td class="info-label">Objek:</td>
                    <td class="info-value">{{ $hierarchy['objek']->kode }} - {{ $hierarchy['objek']->nama }}</td>
                </tr>
                <tr>
                    <td class="info-label">Rincian Objek:</td>
                    <td class="info-value">{{ $hierarchy['rincianObjek']->kode }} -
                        {{ $hierarchy['rincianObjek']->nama }}</td>
                </tr>
                <tr>
                    <td class="info-label">Sub Rincian Objek:</td>
                    <td class="info-value">{{ $hierarchy['subRincianObjek']->kode }} -
                        {{ $hierarchy['subRincianObjek']->nama }}</td>
                </tr>
                <tr>
                    <td class="info-label">Sub Sub Rincian Objek:</td>
                    <td class="info-value">{{ $hierarchy['subSubRincianObjek']->kode }} -
                        {{ $hierarchy['subSubRincianObjek']->nama_barang }}</td>
                </tr>
            </table>
        </div>

        <!-- Informasi Dasar Aset -->
        <div class="hierarchy-section section">
            <div class="section-title">Informasi Dasar Aset</div>
            <table class="info-table">
                <tr>
                    <td class="info-label">Kode Barang:</td>
                    <td class="info-value text-bold">{{ $aset->kode_barang }}</td>
                </tr>
                <tr>
                    <td class="info-label">Register:</td>
                    <td class="info-value text-bold">{{ $aset->register }}</td>
                </tr>
                <tr>
                    <td class="info-label">Nama Bidang Barang:</td>
                    <td class="info-value">{{ $aset->nama_bidang_barang }}</td>
                </tr>
                <tr>
                    <td class="info-label">Nama Jenis Barang:</td>
                    <td class="info-value">{{ $aset->nama_jenis_barang }}</td>
                </tr>
                <tr>
                    <td class="info-label">Merk/Type:</td>
                    <td class="info-value">{{ $aset->merk_type ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Bahan:</td>
                    <td class="info-value">{{ $aset->bahan ?: '-' }}</td>
                </tr>
            </table>
        </div>

        <!-- Informasi Teknis -->
        <div class="hierarchy-section section">
            <div class="section-title">Informasi Teknis</div>
            <table class="info-table">
                <tr>
                    <td class="info-label">No. Sertifikat:</td>
                    <td class="info-value">{{ $aset->no_sertifikat ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="info-label">No. Plat Kendaraan:</td>
                    <td class="info-value">{{ $aset->no_plat_kendaraan ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="info-label">No. Pabrik:</td>
                    <td class="info-value">{{ $aset->no_pabrik ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="info-label">No. Casis:</td>
                    <td class="info-value">{{ $aset->no_casis ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Ukuran/Konstruksi:</td>
                    <td class="info-value">{{ $aset->ukuran_barang_konstruksi ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Satuan:</td>
                    <td class="info-value">{{ $aset->satuan }}</td>
                </tr>
            </table>
        </div>

        <!-- Informasi Perolehan & Kondisi -->
        <div class="hierarchy-section section">
            <div class="section-title">Informasi Perolehan & Kondisi</div>
            <table class="info-table">
                <tr>
                    <td class="info-label">Asal Perolehan:</td>
                    <td class="info-value">{{ $aset->asal_perolehan }}</td>
                </tr>
                <tr>
                    <td class="info-label">Tahun Perolehan:</td>
                    <td class="info-value text-bold">{{ $aset->tahun_perolehan }}</td>
                </tr>
                <tr>
                    <td class="info-label">Jumlah Barang:</td>
                    <td class="info-value">{{ number_format($aset->jumlah_barang) }} {{ $aset->satuan }}</td>
                </tr>
                <tr>
                    <td class="info-label">Keadaan Barang:</td>
                    <td class="info-value">
                        <span
                            class="status-badge status-{{ strtolower(str_replace(' ', '-', $aset->keadaan_barang)) }}">
                            {{ $aset->keadaan_barang }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Informasi Finansial -->
        <div class="financial-section section">
            <div class="section-title">Informasi Finansial</div>
            <table class="info-table">
                <tr>
                    <td class="info-label">Harga Satuan:</td>
                    <td class="info-value">Rp {{ number_format($aset->harga_satuan, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="info-label">Total Nilai Aset:</td>
                    <td class="info-value">
                        <span class="financial-highlight">
                            Rp {{ number_format($aset->harga_satuan * $aset->jumlah_barang, 2, ',', '.') }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Foto Bukti Barang -->
        <div class="image-section section">
            <div class="section-title">Dokumentasi Bukti Barang</div>
            @if ($imageBase64)
                <img src="{{ $imageBase64 }}" alt="Bukti Barang {{ $aset->register }}" class="asset-image">
                <p class="image-caption">
                    Nama File: {{ $aset->bukti_barang }}
                </p>
            @else
                <div class="no-image">
                    <p><strong>Tidak ada foto bukti barang yang tersedia</strong></p>
                    <p class="text-small mt-small">Mohon upload foto bukti barang untuk kelengkapan dokumentasi</p>
                </div>
            @endif
        </div>

        <!-- Informasi Dokumen Bukti Berita -->
        @if (isset($hasBuktiBerita) && $hasBuktiBerita)
            <div class="document-info section">
                <p class="info-title">📄 Dokumen Bukti Berita Tersedia</p>
                <p class="info-text">
                    <strong>Nama File:</strong> {{ $aset->bukti_berita }}<br>
                    <span class="warning-text">Catatan:</span> File PDF bukti berita tersedia terpisah. Silakan unduh
                    dari halaman detail aset untuk melihat dokumen lengkap.
                </p>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div class="footer-line">Dokumen ini digenerate secara otomatis pada {{ $generatedAt }}</div>
            <div class="footer-line">Sistem Manajemen Aset - {{ config('app.name', 'Laravel') }}</div>
            <div class="footer-line">Halaman: <span class="pagenum"></span></div>
        </div>
    </div>
</body>

</html>
