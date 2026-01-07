<?php

namespace App\Services\Aset;

use App\Models\{Akun, Kelompok, Jenis, Objek, RincianObjek, SubRincianObjek, SubSubRincianObjek};
use App\Repositories\AsetRepository;

class AsetKodeService
{
    private const KODE_RUSAK_BERAT = '1.5.4.01.01.01.005';

    public function __construct(
        private AsetRepository $asetRepository
    ) {}

    /**
     * Generate kode barang dari hierarchy
     */
    public function generateKodeBarang(array $hierarchyIds): string
    {
        $akun = Akun::find($hierarchyIds['akun_id']);
        $kelompok = Kelompok::find($hierarchyIds['kelompok_id']);
        $jenis = Jenis::find($hierarchyIds['jenis_id']);
        $objek = Objek::find($hierarchyIds['objek_id']);
        $rincianObjek = RincianObjek::find($hierarchyIds['rincian_objek_id']);
        $subRincianObjek = SubRincianObjek::find($hierarchyIds['sub_rincian_objek_id']);
        $subSubRincianObjek = SubSubRincianObjek::find($hierarchyIds['sub_sub_rincian_objek_id']);

        return implode('.', [
            $akun->kode,
            $kelompok->kode,
            $jenis->kode,
            $objek->kode,
            $rincianObjek->kode,
            $subRincianObjek->kode,
            $subSubRincianObjek->kode
        ]);
    }

    /**
     * Generate kode barang untuk rusak berat
     */
    public function generateKodeBarangRusakBerat(): string
    {
        return self::KODE_RUSAK_BERAT;
    }

    /**
     * Determine final kode barang berdasarkan keadaan
     */
    public function determineFinalKodeBarang(string $kodeBarang, string $keadaanBarang): string
    {
        return $keadaanBarang === 'RB'
            ? $this->generateKodeBarangRusakBerat()
            : $kodeBarang;
    }

    /**
     * Generate register dari sequence
     */
    public function generateRegister(int $sequence): string
    {
        return str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get next register number untuk kode barang
     */
    public function getNextRegisterNumber(string $kodeBarang): int
    {
        $lastNumber = $this->asetRepository->getLastRegisterNumber($kodeBarang);
        return $lastNumber + 1;
    }

    /**
     * Generate register preview
     */
    public function generateRegisterPreview(string $kodeBarang, string $keadaanBarang): array
    {
        // Untuk rusak berat, gunakan kode barang RB
        if ($keadaanBarang === 'RB') {
            $kodeBarang = $this->generateKodeBarangRusakBerat();
        }

        $lastNumber = $this->asetRepository->getLastRegisterNumber($kodeBarang);
        $nextNumber = $lastNumber + 1;
        $sequence = $this->generateRegister($nextNumber);

        $infoMessage = "Register terakhir untuk kode barang {$kodeBarang}: " .
            ($lastNumber > 0 ? str_pad($lastNumber, 3, '0', STR_PAD_LEFT) : 'Belum ada');

        if ($keadaanBarang === 'RB') {
            $infoMessage .= " (Kategori: Rusak Berat - register boleh duplikat)";
        }

        return [
            'register_preview' => $sequence,
            'last_number' => $lastNumber,
            'next_number' => $nextNumber,
            'sequence' => $sequence,
            'kode_barang_final' => $kodeBarang,
            'info' => $infoMessage
        ];
    }

    /**
     * Get register info untuk kode barang
     */
    public function getRegisterInfo(string $kodeBarang): array
    {
        $totalAssets = $this->asetRepository->countByKodeBarang($kodeBarang);
        $lastNumber = $this->asetRepository->getLastRegisterNumber($kodeBarang);
        $nextNumber = $lastNumber + 1;

        return [
            'kode_barang' => $kodeBarang,
            'total_existing_assets' => $totalAssets,
            'last_register_number' => $lastNumber,
            'next_register_number' => $nextNumber,
            'next_register_formatted' => str_pad($nextNumber, 3, '0', STR_PAD_LEFT),
            'info_message' => $totalAssets > 0
                ? "Sudah ada {$totalAssets} aset dengan kode ini. Register berikutnya: " . str_pad($nextNumber, 3, '0', STR_PAD_LEFT)
                : "Belum ada aset dengan kode ini. Register akan dimulai dari: 001"
        ];
    }
}
