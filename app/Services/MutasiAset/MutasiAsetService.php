<?php

namespace App\Services\MutasiAset;

use App\Models\Aset;
use App\Models\MutasiAset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MutasiAsetService
{
    public function __construct(
        private MutasiAsetFileService $fileService
    ) {}

    /**
     * Create new mutation with transaction
     */
    public function createMutasi(array $data, $beritaAcaraFile): MutasiAset
    {
        return DB::transaction(function () use ($data, $beritaAcaraFile) {
            $aset = Aset::findOrFail($data['aset_id']);

            // Validate ruangan tidak sama
            $this->validateRuanganDifferent($aset->ruangan, $data['ruangan_tujuan']);

            // Upload berita acara
            $beritaAcaraPath = $this->fileService->uploadBeritaAcara($beritaAcaraFile);

            // Create mutasi record
            $mutasi = $this->storeMutasiRecord($aset, $data, $beritaAcaraPath);

            // Update aset location
            $this->updateAssetLocation($aset, $data);

            $this->logMutasiCreated($mutasi, $aset);

            return $mutasi;
        });
    }

    /**
     * Validate that destination room is different from source
     */
    private function validateRuanganDifferent(string $ruanganAsal, string $ruanganTujuan): void
    {
        if ($ruanganAsal === $ruanganTujuan) {
            throw new \InvalidArgumentException('Ruangan tujuan tidak boleh sama dengan ruangan asal.');
        }
    }

    /**
     * Store mutation record
     */
    private function storeMutasiRecord(Aset $aset, array $data, string $beritaAcaraPath): MutasiAset
    {
        return MutasiAset::create([
            'aset_id' => $data['aset_id'],
            'user_id' => Auth::id(),
            'ruangan_asal' => $aset->ruangan,
            'ruangan_tujuan' => $data['ruangan_tujuan'],
            'lokasi_asal' => $aset->lokasi_barang,
            'lokasi_tujuan' => $data['lokasi_tujuan'] ?? null,
            'tanggal_mutasi' => $data['tanggal_mutasi'],
            'nomor_surat' => $data['nomor_surat'],
            'berita_acara_path' => $beritaAcaraPath,
            'keterangan' => $data['keterangan'] ?? null,
        ]);
    }

    /**
     * Update asset location after mutation
     */
    private function updateAssetLocation(Aset $aset, array $data): void
    {
        $updateData = [
            'ruangan' => $data['ruangan_tujuan']
        ];

        // Update lokasi if provided
        if (!empty($data['lokasi_tujuan'])) {
            $updateData['lokasi_barang'] = $data['lokasi_tujuan'];
        }

        $aset->update($updateData);
    }

    /**
     * Log mutation creation
     */
    private function logMutasiCreated(MutasiAset $mutasi, Aset $aset): void
    {
        Log::info('Mutasi Created', [
            'mutasi_id' => $mutasi->id,
            'aset_id' => $aset->id,
            'user_id' => Auth::id()
        ]);
    }
}
