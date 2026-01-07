<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Informasi Aset - {{ $aset->register }}</title>

    <style>
        @page {
            margin: 2cm 1.5cm;
            font-family: DejaVu Sans, sans-serif;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #1f2937;
        }

        .container {
            width: 100%;
        }

        /* JUDUL */
        .asset-title {
            text-align: center;
            margin-bottom: 14px;
        }

        .asset-title h1 {
            font-size: 14px;
            margin-bottom: 0;
        }

        /* FOTO */
        .image-wrapper {
            text-align: center;
            margin-bottom: 16px;
        }

        .asset-image {
            max-height: 260px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
        }

        .no-image {
            border: 1px dashed #9ca3af;
            padding: 20px;
            font-style: italic;
            color: #6b7280;
        }

        /* SECTION */
        .section {
            margin-bottom: 14px;
        }

        .section-title {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 6px;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 4px 6px;
            vertical-align: top;
        }

        .label {
            width: 32%;
            color: #374151;
        }

        .value {
            width: 68%;
            font-weight: 500;
        }

        .multiline {
            white-space: pre-wrap;
            line-height: 1.5;
        }

        .status {
            display: inline-block;
            padding: 2px 8px;
            font-size: 9px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
        }

        /* FOOTER */
        .footer {
            position: fixed;
            bottom: 1cm;
            left: 1.5cm;
            right: 1.5cm;
            font-size: 8px;
            color: #6b7280;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            padding-top: 4px;
        }
    </style>
</head>

<body>
    <div class="container">

        <!-- JUDUL DOKUMEN -->
        <div class="asset-title">
            <h1>Detail Informasi Aset</h1>
        </div>

        <!-- FOTO ASET -->
        <div class="image-wrapper">
            @if ($imageBase64)
                <img src="{{ $imageBase64 }}" class="asset-image">
            @else
                <div class="no-image">Tidak tersedia foto aset</div>
            @endif
        </div>

        <!-- INFORMASI UTAMA -->
        <div class="section">
            <div class="section-title">Informasi Utama</div>
            <table>
                <tr>
                    <td class="label">Kode Barang</td>
                    <td class="value">{{ $aset->kode_barang }}</td>
                </tr>
                <tr>
                    <td class="label">Register</td>
                    <td class="value">{{ $aset->register }}</td>
                </tr>
                <tr>
                    <td class="label">Nama Jenis Barang</td>
                    <td class="value">{{ $aset->nama_jenis_barang }}</td>
                </tr>
                <tr>
                    <td class="label">Merk / Type</td>
                    <td class="value">{{ $aset->merk_type ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Lokasi Barang</td>
                    <td class="value">{{ $aset->lokasi_barang ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Ruangan</td>
                    <td class="value">{{ $aset->ruangan ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Kondisi</td>
                    <td class="value">
                        <span class="status">{{ $aset->keadaan_barang }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">Keterangan</td>
                    <td class="value multiline">{{ $aset->keterangan ?: '-' }}</td>
                </tr>
            </table>
        </div>

        <!-- INFORMASI TEKNIS -->
        <div class="section">
            <div class="section-title">Informasi Teknis</div>
            <table>
                <tr>
                    <td class="label">No. Sertifikat</td>
                    <td class="value">{{ $aset->no_sertifikat ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="label">No. Plat Kendaraan</td>
                    <td class="value">{{ $aset->no_plat_kendaraan ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="label">No. Casis</td>
                    <td class="value">{{ $aset->no_casis ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Ukuran / Konstruksi</td>
                    <td class="value">{{ $aset->ukuran_barang_konstruksi ?: '-' }}</td>
                </tr>
            </table>
        </div>

        <!-- INFORMASI PEROLEHAN -->
        <div class="section">
            <div class="section-title">Informasi Perolehan</div>
            <table>
                <tr>
                    <td class="label">Asal Perolehan</td>
                    <td class="value">{{ $aset->asal_perolehan }}</td>
                </tr>
                <tr>
                    <td class="label">Tahun Perolehan</td>
                    <td class="value">{{ $aset->tahun_perolehan }}</td>
                </tr>
                <tr>
                    <td class="label">Jumlah Barang</td>
                    <td class="value">{{ $aset->jumlah_barang }} {{ $aset->satuan }}</td>
                </tr>
            </table>
        </div>

        <!-- NILAI ASET -->
        <div class="section">
            <div class="section-title">Nilai Aset</div>
            <table>
                <tr>
                    <td class="label">Harga Satuan</td>
                    <td class="value">Rp {{ number_format($aset->harga_satuan, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label">Total Nilai</td>
                    <td class="value">
                        Rp {{ number_format($aset->harga_satuan * $aset->jumlah_barang, 2, ',', '.') }}
                    </td>
                </tr>
            </table>
        </div>

    </div>

    <div class="footer">
        Digenerate pada {{ $generatedAt }} • {{ config('app.name') }}
    </div>

</body>

</html>
