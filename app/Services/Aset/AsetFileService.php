<?php

namespace App\Services\Aset;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class AsetFileService
{
    protected string $disk = 'public';

    protected string $buktiBarangDir = 'bukti_barang';
    protected string $buktiBeritaDir = 'bukti_berita';

    /**
     * Handle upload file saat CREATE
     */
    public function handleUploadsForCreate(
        ?UploadedFile $buktiBarang,
        ?UploadedFile $buktiBerita
    ): array {
        return [
            'bukti_barang' => $buktiBarang
                ? $this->uploadBuktiBarang($buktiBarang)
                : null,

            'bukti_berita' => $buktiBerita
                ? $this->uploadBuktiBerita($buktiBerita)
                : null,
        ];
    }

    /**
     * Handle upload file saat UPDATE (replace file lama jika ada)
     */
    public function handleUploadsForUpdate(
        ?UploadedFile $buktiBarang,
        ?UploadedFile $buktiBerita,
        ?string $oldBuktiBarang,
        ?string $oldBuktiBerita,
        int $asetId
    ): array {
        $files = [];

        if ($buktiBarang) {
            $this->deleteBuktiBarang($oldBuktiBarang);
            $files['bukti_barang'] = $this->uploadBuktiBarang($buktiBarang, $asetId);
        }

        if ($buktiBerita) {
            $this->deleteBuktiBerita($oldBuktiBerita);
            $files['bukti_berita'] = $this->uploadBuktiBerita($buktiBerita, $asetId);
        }

        return $files;
    }

    /**
     * Upload bukti barang (image)
     */
    public function uploadBuktiBarang(UploadedFile $file, ?int $asetId = null): string
    {
        return $this->uploadFile($file, $this->buktiBarangDir, 'bukti_barang', $asetId);
    }

    /**
     * Upload bukti berita (PDF)
     */
    public function uploadBuktiBerita(UploadedFile $file, ?int $asetId = null): string
    {
        return $this->uploadFile($file, $this->buktiBeritaDir, 'bukti_berita', $asetId);
    }

    /**
     * Upload file generic
     */
    private function uploadFile(
        UploadedFile $file,
        string $directory,
        string $prefix,
        ?int $asetId = null
    ): string {
        if (!$file->isValid()) {
            Log::error('Invalid uploaded file', [
                'directory' => $directory,
                'original_name' => $file->getClientOriginalName()
            ]);
            throw new Exception("File {$prefix} tidak valid");
        }

        $fileName = $this->generateFileName($prefix, $file->extension(), $asetId);

        $file->storeAs($directory, $fileName, $this->disk);

        Log::info('File uploaded', [
            'directory' => $directory,
            'filename' => $fileName
        ]);

        return $fileName;
    }

    /**
     * Delete semua file aset
     */
    public function deleteAsetFiles(?string $buktiBarang, ?string $buktiBerita): void
    {
        $this->deleteBuktiBarang($buktiBarang);
        $this->deleteBuktiBerita($buktiBerita);
    }

    /**
     * Delete bukti barang
     */
    public function deleteBuktiBarang(?string $fileName): void
    {
        $this->deleteFile($this->buktiBarangDir, $fileName);
    }

    /**
     * Delete bukti berita
     */
    public function deleteBuktiBerita(?string $fileName): void
    {
        $this->deleteFile($this->buktiBeritaDir, $fileName);
    }

    /**
     * Delete file generic
     */
    private function deleteFile(string $directory, ?string $fileName): void
    {
        if (!$fileName) {
            return;
        }

        $path = "{$directory}/{$fileName}";

        if (Storage::disk($this->disk)->exists($path)) {
            Storage::disk($this->disk)->delete($path);

            Log::info('File deleted', ['path' => $path]);
        }
    }

    /**
     * Cek apakah bukti barang ada
     */
    public function buktiBarangExists(string $fileName): bool
    {
        return Storage::disk($this->disk)->exists($this->buktiBarangDir . '/' . $fileName);
    }

    /**
     * Cek apakah bukti berita ada
     */
    public function buktiBeritaExists(string $fileName): bool
    {
        return Storage::disk($this->disk)->exists($this->buktiBeritaDir . '/' . $fileName);
    }

    /**
     * Get full path bukti barang
     */
    public function getBuktiBarangPath(string $fileName): string
    {
        return storage_path('app/' . $this->disk . '/' . $this->buktiBarangDir . '/' . $fileName);
    }

    /**
     * Get full path bukti berita
     */
    public function getBuktiBeritaPath(string $fileName): string
    {
        return storage_path('app/' . $this->disk . '/' . $this->buktiBeritaDir . '/' . $fileName);
    }

    /**
     * Generate standardized filename
     */
    private function generateFileName(string $prefix, string $extension, ?int $asetId = null): string
    {
        $base = $prefix;

        if ($asetId) {
            $base .= '_' . $asetId;
        }

        return $base . '_' . time() . '.' . $extension;
    }
}
