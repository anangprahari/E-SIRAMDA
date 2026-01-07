<?php

namespace App\Services\AsetLancar;

use App\Repositories\AsetLancarRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AsetLancarExportService
{
    public function __construct(
        protected AsetLancarRepository $repository
    ) {}

    /**
     * Export AsetLancar data to Excel.
     */
    public function export(array $filters): void
    {
        $asetLancars = $this->repository->getAllFiltered($filters);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $this->setupHeader($sheet);
        $this->setupTableHeaders($sheet);

        $totals = $this->fillDataRows($sheet, $asetLancars);
        $this->addTotalRow($sheet, $totals);

        $this->applyFormatting($sheet, count($asetLancars));

        $this->outputExcel($spreadsheet);
    }

    /**
     * Setup document header.
     */
    protected function setupHeader($sheet): void
    {
        $sheet->setCellValue('A1', 'LAPORAN ASET LANCAR');
        $sheet->mergeCells('A1:M1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Tanggal: ' . date('d F Y'));
        $sheet->mergeCells('A2:M2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    /**
     * Setup table headers.
     */
    protected function setupTableHeaders($sheet): void
    {
        // Main headers (Row 4)
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Kode Rekening');
        $sheet->setCellValue('C4', 'Uraian Rekening');
        $sheet->setCellValue('D4', 'Nama Kegiatan');
        $sheet->setCellValue('E4', 'Jenis Barang');
        $sheet->setCellValue('F4', 'Saldo Awal');
        $sheet->setCellValue('I4', 'Mutasi');
        $sheet->setCellValue('L4', 'Saldo Akhir');

        // Merge main header cells
        $mainHeaderMerges = [
            'A4:A5',
            'B4:B5',
            'C4:C5',
            'D4:D5',
            'E4:E5',
            'F4:H4',
            'I4:K4',
            'L4:M4'
        ];
        foreach ($mainHeaderMerges as $merge) {
            $sheet->mergeCells($merge);
        }

        // Sub headers (Row 5)
        $sheet->setCellValue('F5', 'Unit Barang');
        $sheet->setCellValue('G5', 'Harga Satuan');
        $sheet->setCellValue('H5', 'Nilai Total');
        $sheet->setCellValue('I5', 'Tambah');
        $sheet->setCellValue('J5', 'Kurang');
        $sheet->setCellValue('K5', 'Nilai Total');
        $sheet->setCellValue('L5', 'Unit Barang');
        $sheet->setCellValue('M5', 'Nilai Total');

        $this->styleHeaders($sheet);
    }

    /**
     * Apply styling to headers.
     */
    protected function styleHeaders($sheet): void
    {
        // Font and alignment
        $sheet->getStyle('A4:M5')->getFont()->setBold(true);
        $sheet->getStyle('A4:M5')->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A4:M5')->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Background colors
        $this->applyHeaderBackgroundColors($sheet);
    }

    /**
     * Apply background colors to header sections.
     */
    protected function applyHeaderBackgroundColors($sheet): void
    {
        $sheet->getStyle('F4:H5')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE3F2FD');

        $sheet->getStyle('I4:K5')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE8F5E9');

        $sheet->getStyle('L4:M5')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFF3E0');
    }

    /**
     * Fill data rows and calculate totals.
     */
    protected function fillDataRows($sheet, $asetLancars): array
    {
        $row = 6;
        $totals = [
            'saldo_awal' => 0,
            'mutasi' => 0,
            'saldo_akhir' => 0
        ];

        foreach ($asetLancars as $i => $aset) {
            $mutasiTotal = $aset->mutasi_tambah_nilai_total - $aset->mutasi_kurang_nilai_total;

            $sheet->fromArray([
                $i + 1,
                $aset->rekeningUraian->kode_rekening,
                $aset->rekeningUraian->uraian,
                $aset->nama_kegiatan,
                $aset->uraian_jenis_barang,
                $aset->saldo_awal_unit,
                $aset->saldo_awal_harga_satuan,
                $aset->saldo_awal_total,
                $aset->mutasi_tambah_unit ?: '-',
                $aset->mutasi_kurang_unit ? '-' . $aset->mutasi_kurang_unit : '-',
                $mutasiTotal,
                $aset->saldo_akhir_unit,
                $aset->saldo_akhir_total,
            ], null, 'A' . $row);

            $sheet->getStyle("A{$row}:M{$row}")
                ->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN);

            $totals['saldo_awal'] += $aset->saldo_awal_total;
            $totals['mutasi'] += $mutasiTotal;
            $totals['saldo_akhir'] += $aset->saldo_akhir_total;

            $row++;
        }

        return $totals;
    }

    /**
     * Add total row at the bottom.
     */
    protected function addTotalRow($sheet, array $totals): void
    {
        $row = $sheet->getHighestRow() + 1;

        $sheet->setCellValue("A{$row}", 'TOTAL');
        $sheet->mergeCells("A{$row}:G{$row}");
        $sheet->setCellValue("H{$row}", $totals['saldo_awal']);
        $sheet->setCellValue("K{$row}", $totals['mutasi']);
        $sheet->setCellValue("M{$row}", $totals['saldo_akhir']);

        $sheet->getStyle("A{$row}:M{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:M{$row}")
            ->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THICK);
    }

    /**
     * Apply formatting to columns and cells.
     */
    protected function applyFormatting($sheet, int $dataRowCount): void
    {
        // Auto-size columns
        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Number formatting
        $lastRow = 6 + $dataRowCount;
        $sheet->getStyle("G6:G{$lastRow}")
            ->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("H6:H{$lastRow}")
            ->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("K6:K{$lastRow}")
            ->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("M6:M{$lastRow}")
            ->getNumberFormat()->setFormatCode('#,##0');
    }

    /**
     * Output Excel file to browser.
     */
    protected function outputExcel(Spreadsheet $spreadsheet): void
    {
        $writer = new Xlsx($spreadsheet);
        $filename = 'aset_lancar_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
