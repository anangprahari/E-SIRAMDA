<?php

namespace App\Services\Aset;

use App\Repositories\AsetRepository;
use App\Exports\AsetExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AsetExportService
{
    public function __construct(
        private AsetRepository $asetRepository
    ) {}

    /**
     * Validate export request
     */
    public function validateExportRequest(array $filters): ?string
    {
        // Validasi rentang tahun
        if (!empty($filters['tahun_dari']) && !empty($filters['tahun_sampai'])) {
            if ($filters['tahun_dari'] > $filters['tahun_sampai']) {
                return 'Tahun dari tidak boleh lebih besar dari tahun sampai.';
            }
        }

        return null;
    }

    /**
     * Check if data exists for export
     */
    public function hasDataToExport(array $filters): bool
    {
        $query = $this->asetRepository->getFilteredQuery($filters);
        return $query->count() > 0;
    }

    /**
     * Get total records count
     */
    public function getTotalRecords(array $filters): int
    {
        $query = $this->asetRepository->getFilteredQuery($filters);
        return $query->count();
    }

    /**
     * Generate export filename
     */
    public function generateFilename(array $filters): string
    {
        $filename = 'Data_Aset_' . date('Y-m-d_H-i-s');

        $filterInfo = [];

        if (!empty($filters['search'])) {
            $filterInfo[] = 'Search';
        }

        if (!empty($filters['tahun_perolehan'])) {
            $filterInfo[] = 'Tahun' . $filters['tahun_perolehan'];
        }

        if (!empty($filters['tahun_dari']) && !empty($filters['tahun_sampai'])) {
            $filterInfo[] = 'Tahun' . $filters['tahun_dari'] . '-' . $filters['tahun_sampai'];
        } elseif (!empty($filters['tahun_dari'])) {
            $filterInfo[] = 'Dari' . $filters['tahun_dari'];
        } elseif (!empty($filters['tahun_sampai'])) {
            $filterInfo[] = 'Sampai' . $filters['tahun_sampai'];
        }

        if (!empty($filters['keadaan_barang'])) {
            $filterInfo[] = str_replace(' ', '', $filters['keadaan_barang']);
        }

        if (!empty($filters['ruangan'])) {
            $filterInfo[] = 'Ruangan';
        }

        if (!empty($filterInfo)) {
            $filename .= '_' . implode('_', $filterInfo);
        }

        $filename .= '.xlsx';

        return $filename;
    }

    /**
     * Log export activity
     */
    public function logExport(array $filters, int $totalRecords, string $filename): void
    {
        Log::info('Exporting assets to Excel', [
            'user_id' => Auth::id() ?? 'system',
            'total_records' => $totalRecords,
            'filters' => $filters,
            'filename' => $filename
        ]);
    }

    /**
     * Prepare memory and timeout for large exports
     */
    public function prepareForLargeExport(int $totalRecords): void
    {
        if ($totalRecords > 5000) {
            ini_set('memory_limit', '512M');
            set_time_limit(300);
        }
    }

    /**
     * Export to Excel
     */
    public function export(array $filters)
    {
        // Validate
        $validationError = $this->validateExportRequest($filters);
        if ($validationError) {
            throw new \Exception($validationError);
        }

        // Check if data exists
        if (!$this->hasDataToExport($filters)) {
            throw new \Exception('Tidak ada data untuk diekspor dengan filter yang dipilih.');
        }

        // Get total records
        $totalRecords = $this->getTotalRecords($filters);

        // Generate filename
        $filename = $this->generateFilename($filters);

        // Log export
        $this->logExport($filters, $totalRecords, $filename);

        // Prepare for large export
        $this->prepareForLargeExport($totalRecords);

        // Export
        return Excel::download(
            new AsetExport(
                $filters['search'] ?? null,
                $filters['tahun_perolehan'] ?? null,
                $filters['keadaan_barang'] ?? null,
                $filters['tahun_dari'] ?? null,
                $filters['tahun_sampai'] ?? null,
                $filters['ruangan'] ?? null
            ),
            $filename,
            \Maatwebsite\Excel\Excel::XLSX
        );
    }
}
