<?php

namespace App\Exports;

use App\Models\Aset;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AsetExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle, WithChunkReading
{
    protected $search;
    protected $tahunPerolehan;
    protected $keadaanBarang;
    protected $tahunDari;
    protected $tahunSampai;
    protected $ruangan;
    private $rowNumber = 0;

    public function __construct(
        $search = null,
        $tahunPerolehan = null,
        $keadaanBarang = null,
        $tahunDari = null,
        $tahunSampai = null,
        $ruangan = null
    ) {
        $this->search = $search;
        $this->tahunPerolehan = $tahunPerolehan;
        $this->keadaanBarang = $keadaanBarang;
        $this->tahunDari = $tahunDari;
        $this->tahunSampai = $tahunSampai;
        $this->ruangan = $ruangan;
    }


    public function query()
    {
        return Aset::with([
            'subSubRincianObjek.subRincianObjek.rincianObjek.objek.jenis.kelompok.akun'
        ])
            // Search
            ->search($this->search)

            // Filter tahun perolehan (single year)
            ->filterTahunPerolehan($this->tahunPerolehan)

            // Filter keadaan barang
            ->filterKeadaanBarang($this->keadaanBarang)

            // ✅ FILTER RUANGAN (FIX UTAMA)
            ->filterRuangan($this->ruangan)

            // Filter rentang tahun perolehan
            ->when(
                $this->tahunDari || $this->tahunSampai,
                function ($query) {
                    $query->where(function ($q) {
                        if ($this->tahunDari && $this->tahunSampai) {
                            $q->whereBetween('tahun_perolehan', [
                                $this->tahunDari,
                                $this->tahunSampai
                            ]);
                        } elseif ($this->tahunDari) {
                            $q->where('tahun_perolehan', '>=', $this->tahunDari);
                        } elseif ($this->tahunSampai) {
                            $q->where('tahun_perolehan', '<=', $this->tahunSampai);
                        }
                    });
                }
            )

            // Ordering sama dengan index()
            ->orderByRaw('
            CAST(SUBSTRING_INDEX(kode_barang, ".", 1) AS UNSIGNED),
            CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_barang, ".", 2), ".", -1) AS UNSIGNED),
            CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_barang, ".", 3), ".", -1) AS UNSIGNED),
            CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_barang, ".", 4), ".", -1) AS UNSIGNED),  
            CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_barang, ".", 5), ".", -1) AS UNSIGNED),
            CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_barang, ".", 6), ".", -1) AS UNSIGNED),
            CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_barang, ".", 7), ".", -1) AS UNSIGNED),
            CAST(SUBSTRING_INDEX(kode_barang, ".", -1) AS UNSIGNED)
        ');
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Barang',
            'Register',
            'Nama Bidang Barang',
            'Nama Jenis Barang',
            'Akun',
            'Kelompok',
            'Jenis',
            'Objek',
            'Rincian Objek',
            'Sub Rincian Objek',
            'Sub Sub Rincian Objek',
            'Merk/Type',
            'No. Sertifikat',
            'No. Plat Kendaraan',
            'No. Pabrik',
            'No. Casis',
            'Bahan',
            'Asal Perolehan',
            'Tahun Perolehan',
            'Ukuran/Konstruksi',
            'Satuan',
            'Keadaan Barang',
            'Jumlah Barang',
            'Harga Satuan',
            'Total Harga',
            'Lokasi Barang',
            'Ruangan',
            'Keterangan',
            'Tanggal Dibuat',
            'Tanggal Diupdate'
        ];
    }

    public function map($aset): array
    {
        $this->rowNumber++;

        $akun = optional(optional(optional(optional(optional(optional($aset->subSubRincianObjek)
            ->subRincianObjek)->rincianObjek)->objek)->jenis)->kelompok)->akun->nama ?? '-';

        $kelompok = optional(optional(optional(optional(optional($aset->subSubRincianObjek)
            ->subRincianObjek)->rincianObjek)->objek)->jenis)->kelompok->nama ?? '-';

        $jenis = optional(optional(optional(optional($aset->subSubRincianObjek)
            ->subRincianObjek)->rincianObjek)->objek)->jenis->nama ?? '-';

        $objek = optional(optional(optional($aset->subSubRincianObjek)
            ->subRincianObjek)->rincianObjek)->objek->nama ?? '-';

        $rincianObjek = optional(optional($aset->subSubRincianObjek)
            ->subRincianObjek)->rincianObjek->nama ?? '-';

        $subRincianObjek = optional($aset->subSubRincianObjek)
            ->subRincianObjek->nama ?? '-';

        $subSubRincianObjek = optional($aset->subSubRincianObjek)
            ->nama_barang ?? '-';

        return [
            $this->rowNumber,
            $aset->kode_barang ?? '-',
            $aset->register ?? '-',
            $aset->nama_bidang_barang ?? '-',
            $aset->nama_jenis_barang ?? '-',
            $akun,
            $kelompok,
            $jenis,
            $objek,
            $rincianObjek,
            $subRincianObjek,
            $subSubRincianObjek,
            $aset->merk_type ?? '-',
            $aset->no_sertifikat ?? '-',
            $aset->no_plat_kendaraan ?? '-',
            $aset->no_pabrik ?? '-',
            $aset->no_casis ?? '-',
            $aset->bahan ?? '-',
            $aset->asal_perolehan ?? '-',
            $aset->tahun_perolehan ?? '-',
            $aset->ukuran_barang_konstruksi ?? '-',
            $aset->satuan ?? '-',
            $aset->keadaan_barang ?? '-',
            $aset->jumlah_barang ?? 0,
            $this->formatCurrency($aset->harga_satuan ?? 0),
            $this->formatCurrency(($aset->harga_satuan ?? 0) * ($aset->jumlah_barang ?? 0)),
            $aset->lokasi_barang ?? '-',
            $aset->ruangan ?? '-',
            $aset->keterangan ?? '-',
            $aset->created_at ? $aset->created_at->format('d/m/Y H:i') : '-',
            $aset->updated_at ? $aset->updated_at->format('d/m/Y H:i') : '-',
        ];
    }


    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ],

            // DATA CELLS
            'A2:AD1000' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC']
                    ]
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ],

            // NOMOR
            'A:A' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ]
            ],

            // CURRENCY
            'Y:Z' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ]
            ]
        ];
    }

    public function title(): string
    {
        return 'Data Aset';
    }

    public function chunkSize(): int
    {
        return 1000; // Process 1000 records at a time
    }

    private function formatCurrency($amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
