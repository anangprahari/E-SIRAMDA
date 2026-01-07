<?php

namespace App\Services\Aset;

use App\Models\Aset;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;
use ZipArchive;

class AsetLabelService
{
    /**
     * Generate label image for an asset
     *
     * @param Aset $aset
     * @return array ['content' => binary, 'filename' => string, 'mime' => string]
     * @throws \Exception
     */
    public function generateLabelImage(Aset $aset): array
    {
        try {
            // Prepare data for template
            $data = $this->prepareLabelData($aset);

            // Render Blade template to HTML
            $html = View::make('asets.label.template', $data)->render();

            // Convert HTML to PNG using Browsershot
            $imageContent = Browsershot::html($html)
                ->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
                ->windowSize(920, 360)
                ->deviceScaleFactor(2)
                ->format('png')
                ->screenshot();

            $filename = $this->generateFilename($aset);

            return [
                'content' => $imageContent,
                'filename' => $filename,
                'mime' => 'image/png'
            ];
        } catch (\Exception $e) {
            Log::error('Error generating label image', [
                'aset_id' => $aset->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw new \Exception('Gagal membuat label: ' . $e->getMessage());
        }
    }

    /**
     * Batch generate labels and package them into ZIP
     *
     * @param array $asetIds
     * @return string Path to generated ZIP file
     * @throws \Exception
     */
    public function generateBatchLabels(array $asetIds): string
    {
        // Validate input
        if (empty($asetIds)) {
            throw new \Exception('Tidak ada aset yang dipilih');
        }

        $tempDir = null;
        $zipPath = null;

        try {
            // Fetch assets
            $asets = Aset::whereIn('id', $asetIds)->get();

            if ($asets->isEmpty()) {
                throw new \Exception('Aset tidak ditemukan');
            }

            // Create temporary directory for storing PNG files
            $tempDir = storage_path('app/temp/labels/' . uniqid('batch_', true));

            if (!is_dir($tempDir) && !mkdir($tempDir, 0755, true)) {
                throw new \Exception('Gagal membuat direktori sementara');
            }

            // Generate label images
            $generatedFiles = [];
            foreach ($asets as $aset) {
                try {
                    // Reuse existing single-label logic
                    $label = $this->generateLabelImage($aset);

                    // Save to temp directory
                    $filePath = $tempDir . '/' . $label['filename'];
                    file_put_contents($filePath, $label['content']);

                    $generatedFiles[] = [
                        'path' => $filePath,
                        'name' => $label['filename']
                    ];

                    Log::info('Label generated for batch', [
                        'aset_id' => $aset->id,
                        'filename' => $label['filename']
                    ]);
                } catch (\Exception $e) {
                    // Log but continue with other assets
                    Log::warning('Failed to generate label in batch', [
                        'aset_id' => $aset->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            if (empty($generatedFiles)) {
                throw new \Exception('Gagal membuat label untuk semua aset yang dipilih');
            }

            // Create ZIP archive
            $zipPath = storage_path('app/temp/label-aset-terpilih-' . time() . '.zip');
            $zip = new ZipArchive();

            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new \Exception('Gagal membuat file ZIP');
            }

            // Add files to ZIP
            foreach ($generatedFiles as $file) {
                $zip->addFile($file['path'], $file['name']);
            }

            $zip->close();

            // Clean up temporary PNG files
            $this->cleanupTempFiles($tempDir);

            Log::info('Batch labels generated successfully', [
                'total_assets' => count($asets),
                'generated_labels' => count($generatedFiles),
                'zip_path' => $zipPath
            ]);

            return $zipPath;
        } catch (\Exception $e) {
            // Clean up on error
            if ($tempDir && is_dir($tempDir)) {
                $this->cleanupTempFiles($tempDir);
            }

            if ($zipPath && file_exists($zipPath)) {
                @unlink($zipPath);
            }

            Log::error('Error generating batch labels', [
                'asset_ids' => $asetIds,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw new \Exception('Gagal membuat label: ' . $e->getMessage());
        }
    }

    /**
     * Clean up temporary files and directory
     *
     * @param string $directory
     * @return void
     */
    private function cleanupTempFiles(string $directory): void
    {
        if (!is_dir($directory)) {
            return;
        }

        try {
            $files = glob($directory . '/*');

            foreach ($files as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }

            @rmdir($directory);

            Log::debug('Cleaned up temporary files', ['directory' => $directory]);
        } catch (\Exception $e) {
            Log::warning('Failed to cleanup temp files', [
                'directory' => $directory,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Prepare data for label template
     *
     * @param Aset $aset
     * @return array
     */
    private function prepareLabelData(Aset $aset): array
    {
        // Generate full kode barang with register (padded to 3 digits)
        $fullKodeBarang = $this->generateFullKodeBarang($aset->kode_barang, $aset->register);

        return [
            'nama_jenis_barang' => $aset->nama_jenis_barang,
            'kode_barang' => $aset->kode_barang,
            'full_kode_barang' => $fullKodeBarang,
            'ruangan' => $aset->ruangan ?? '-',
            'tahun_perolehan' => $aset->tahun_perolehan,
            'lokasi_barang' => $aset->lokasi_barang ?? '-',
            'logo_pemprov_path' => public_path('assets/label/logo-pemprov.png'),
            'logo_kominfo_path' => public_path('assets/label/logo-kominfo.png'),
        ];
    }

    /**
     * Generate full kode barang with register
     *
     * @param string $kodeBarang
     * @param string|null $register
     * @return string
     */
    private function generateFullKodeBarang(string $kodeBarang, ?string $register): string
    {
        // If register is empty or null, use "000" as default
        if (empty($register)) {
            $register = '000';
        }

        // Pad register to 3 digits with leading zeros
        $paddedRegister = str_pad($register, 3, '0', STR_PAD_LEFT);

        // Combine kode_barang + register
        return $kodeBarang . '.' . $paddedRegister;
    }

    /**
     * Generate filename for label download
     *
     * @param Aset $aset
     * @return string
     */
    private function generateFilename(Aset $aset): string
    {
        // Generate full kode barang with register
        $fullKodeBarang = $this->generateFullKodeBarang($aset->kode_barang, $aset->register);

        // Replace dots with hyphens for filename safety
        $safeKode = str_replace('.', '-', $fullKodeBarang);

        return "label-aset-{$safeKode}.png";
    }
}
