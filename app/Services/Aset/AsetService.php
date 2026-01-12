<?php

namespace App\Services\Aset;

use App\Models\Aset;
use App\Repositories\AsetRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AsetService
{
    public function __construct(
        private AsetRepository $asetRepository,
        private AsetKodeService $kodeService,
        private AsetFileService $fileService
    ) {}

    /**
     * Store aset dengan multiple items
     */
    public function store(array $validatedData, $buktiBarangFile, $buktiBeritaFile): array
    {
        return DB::transaction(function () use ($validatedData, $buktiBarangFile, $buktiBeritaFile) {
            // Handle file uploads
            $files = $this->fileService->handleUploadsForCreate($buktiBarangFile, $buktiBeritaFile);

            // Tentukan kode barang final
            $finalKodeBarang = $this->kodeService->determineFinalKodeBarang(
                $validatedData['kode_barang'],
                $validatedData['keadaan_barang']
            );

            // Prepare base data
            $baseData = $this->prepareBaseData($validatedData, $files);

            // Get starting register number
            $startingNumber = $this->kodeService->getNextRegisterNumber($finalKodeBarang);

            // Create multiple assets
            $jumlahBarang = (int) $validatedData['jumlah_barang'];
            $createdAssets = [];

            for ($i = 0; $i < $jumlahBarang; $i++) {
                $assetData = $baseData;
                $currentNumber = $startingNumber + $i;
                $sequence = $this->kodeService->generateRegister($currentNumber);

                $assetData['register'] = $sequence;
                $assetData['kode_barang'] = $finalKodeBarang;

                $createdAsset = $this->asetRepository->create($assetData);
                $createdAssets[] = $createdAsset->register;
            }

            return [
                'created_assets' => $createdAssets,
                'final_kode_barang' => $finalKodeBarang,
                'keadaan_barang' => $validatedData['keadaan_barang'],
                'jumlah_barang' => $jumlahBarang
            ];
        });
    }

    /**
     * Update aset
     * 
     * ⚠️ KEBIJAKAN: 
     * - Hierarki LOCKED (tidak bisa berubah)
     * - Kode barang LOCKED (tidak bisa berubah via hierarki)
     * - EXCEPTION: Jika status = RB, kode otomatis jadi kode RB
     */
    public function update(Aset $aset, array $validatedData, $buktiBarangFile, $buktiBeritaFile): array
    {
        return DB::transaction(function () use ($aset, $validatedData, $buktiBarangFile, $buktiBeritaFile) {
            // Handle file uploads
            $files = $this->fileService->handleUploadsForUpdate(
                $buktiBarangFile,
                $buktiBeritaFile,
                $aset->bukti_barang,
                $aset->bukti_berita,
                $aset->id
            );

            // ⚠️ EXCEPTION: Jika status berubah ke RB, izinkan kode berubah
            if ($validatedData['keadaan_barang'] === 'RB') {
                $finalKodeBarang = $this->kodeService->generateKodeBarangRusakBerat();

                Log::info('Kode barang changed to RB category during edit', [
                    'aset_id' => $aset->id,
                    'old_kode' => $aset->kode_barang,
                    'new_kode' => $finalKodeBarang,
                    'keadaan_barang' => 'RB'
                ]);
            } else {
                // ⚠️ Selain RB: LOCKED - gunakan kode lama
                $finalKodeBarang = $aset->kode_barang;

                // Log jika ada attempt perubahan kode via hierarki
                if (isset($validatedData['kode_barang']) && $validatedData['kode_barang'] !== $finalKodeBarang) {
                    Log::warning('Attempt to change kode_barang via hierarchy blocked', [
                        'aset_id' => $aset->id,
                        'original_kode' => $finalKodeBarang,
                        'attempted_kode' => $validatedData['kode_barang'],
                        'user_id' => Auth::id() ?? 'system'
                    ]);
                }
            }

            // Prepare update data dengan kode barang yang sudah ditentukan
            $updateData = $this->prepareUpdateData($validatedData, $files, $finalKodeBarang);

            // Update aset
            $updated = $this->asetRepository->update($aset, $updateData);

            if (!$updated) {
                throw new \Exception('Gagal mengupdate data aset');
            }

            Log::info('Asset updated successfully', [
                'aset_id' => $aset->id,
                'kode_barang' => $finalKodeBarang,
                'kode_changed' => ($finalKodeBarang !== $aset->kode_barang)
            ]);

            return [
                'final_kode_barang' => $finalKodeBarang,
                'keadaan_barang' => $validatedData['keadaan_barang'],
                'original_kode_barang' => $aset->kode_barang,
                'kode_changed' => ($finalKodeBarang !== $aset->kode_barang)
            ];
        });
    }

    /**
     * Delete aset
     */
    public function delete(Aset $aset): void
    {
        // Delete associated files
        $this->fileService->deleteAsetFiles($aset->bukti_barang, $aset->bukti_berita);

        // Delete aset
        $this->asetRepository->delete($aset);
    }

    /**
     * Prepare base data untuk create
     */
    private function prepareBaseData(array $validatedData, array $files): array
    {
        return [
            'sub_sub_rincian_objek_id' => $validatedData['sub_sub_rincian_objek_id'],
            'nama_bidang_barang' => $validatedData['nama_bidang_barang'],
            'nama_jenis_barang' => $validatedData['nama_jenis_barang'],
            'merk_type' => $validatedData['merk_type'] ?? null,
            'no_sertifikat' => $validatedData['no_sertifikat'] ?? null,
            'no_plat_kendaraan' => $validatedData['no_plat_kendaraan'] ?? null,
            'no_pabrik' => $validatedData['no_pabrik'] ?? null,
            'no_casis' => $validatedData['no_casis'] ?? null,
            'bahan' => $validatedData['bahan'] ?? null,
            'asal_perolehan' => $validatedData['asal_perolehan'],
            'tahun_perolehan' => $validatedData['tahun_perolehan'],
            'ukuran_barang_konstruksi' => $validatedData['ukuran_barang_konstruksi'] ?? null,
            'satuan' => $validatedData['satuan'],
            'keadaan_barang' => $validatedData['keadaan_barang'],
            'jumlah_barang' => 1,
            'harga_satuan' => $validatedData['harga_satuan'],
            'lokasi_barang' => $validatedData['lokasi_barang'] ?? null,
            'keterangan' => $validatedData['keterangan'] ?? null,
            'ruangan' => $validatedData['ruangan'] ?? null,
            'bukti_barang' => $files['bukti_barang'],
            'bukti_berita' => $files['bukti_berita'],
        ];
    }

    /**
     * Prepare update data
     */
    private function prepareUpdateData(array $validatedData, array $files, string $finalKodeBarang): array
    {
        $updateData = [
            'sub_sub_rincian_objek_id' => $validatedData['sub_sub_rincian_objek_id'],
            'nama_bidang_barang' => $validatedData['nama_bidang_barang'],
            'register' => $validatedData['register'],
            'kode_barang' => $finalKodeBarang,
            'nama_jenis_barang' => $validatedData['nama_jenis_barang'],
            'merk_type' => $validatedData['merk_type'] ?? null,
            'no_sertifikat' => $validatedData['no_sertifikat'] ?? null,
            'no_plat_kendaraan' => $validatedData['no_plat_kendaraan'] ?? null,
            'no_pabrik' => $validatedData['no_pabrik'] ?? null,
            'no_casis' => $validatedData['no_casis'] ?? null,
            'bahan' => $validatedData['bahan'] ?? null,
            'asal_perolehan' => $validatedData['asal_perolehan'],
            'tahun_perolehan' => $validatedData['tahun_perolehan'],
            'ukuran_barang_konstruksi' => $validatedData['ukuran_barang_konstruksi'] ?? null,
            'satuan' => $validatedData['satuan'],
            'keadaan_barang' => $validatedData['keadaan_barang'],
            'jumlah_barang' => $validatedData['jumlah_barang'],
            'harga_satuan' => $validatedData['harga_satuan'],
            'lokasi_barang' => $validatedData['lokasi_barang'] ?? null,
            'keterangan' => $validatedData['keterangan'] ?? null,
            'ruangan' => $validatedData['ruangan'] ?? null,
        ];

        // Merge file updates jika ada
        return array_merge($updateData, $files);
    }

    /**
     * Generate success message untuk store
     */
    public function generateStoreSuccessMessage(array $result): string
    {
        $jumlahBarang = $result['jumlah_barang'];
        $createdAssets = $result['created_assets'];

        $message = $jumlahBarang > 1
            ? "Berhasil menambahkan {$jumlahBarang} aset. Register: " . implode(', ', array_slice($createdAssets, 0, 3)) .
            ($jumlahBarang > 3 ? ' dan ' . ($jumlahBarang - 3) . ' lainnya' : '')
            : "Aset berhasil ditambahkan dengan register: {$createdAssets[0]}";

        if ($result['keadaan_barang'] === 'RB') {
            $message .= ". Kode barang telah diubah ke kategori Rusak Berat: {$result['final_kode_barang']}";
        }

        return $message;
    }

    /**
     * Generate success message untuk update
     */
    public function generateUpdateSuccessMessage(array $result): string
    {
        $message = 'Aset berhasil diperbarui';

        // Tambahkan info jika kode berubah karena RB
        if ($result['kode_changed'] && $result['keadaan_barang'] === 'RB') {
            $message .= ". Kode barang telah diubah ke kategori Rusak Berat: {$result['final_kode_barang']}";
        }

        return $message;
    }
}
