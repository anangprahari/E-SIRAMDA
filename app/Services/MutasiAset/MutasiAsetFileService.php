<?php

namespace App\Services\MutasiAset;

use App\Models\MutasiAset;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MutasiAsetFileService
{
    /**
     * Upload berita acara file
     */
    public function uploadBeritaAcara(UploadedFile $file): string
    {
        $filename = 'berita-acara-' . time() . '-' . Str::random(10) . '.pdf';
        return $file->storeAs('berita-acara', $filename, 'public');
    }

    /**
     * Download berita acara
     */
    public function downloadBeritaAcara(MutasiAset $mutasi): BinaryFileResponse
    {
        $filePath = $this->getFilePath($mutasi);
        $downloadName = $this->generateDownloadName($mutasi);

        return response()->download($filePath, $downloadName, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Preview berita acara in browser
     */
    public function previewBeritaAcara(MutasiAset $mutasi): BinaryFileResponse
    {
        $filePath = $this->getFilePath($mutasi);

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline'
        ]);
    }

    /**
     * Get file path and validate existence
     */
    private function getFilePath(MutasiAset $mutasi): string
    {
        if (!$mutasi->berita_acara_path) {
            abort(404, 'File tidak ditemukan');
        }

        $filePath = storage_path('app/public/' . $mutasi->berita_acara_path);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan di penyimpanan');
        }

        return $filePath;
    }

    /**
     * Generate safe download filename
     */
    private function generateDownloadName(MutasiAset $mutasi): string
    {
        return 'Berita-Acara-' . str_replace(['/', '\\'], '-', $mutasi->nomor_surat) . '.pdf';
    }
}
