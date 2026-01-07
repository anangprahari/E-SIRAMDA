<?php

namespace App\Services\AsetLancar;

class AsetLancarCalculationService
{
    /**
     * Calculate all automatic values for AsetLancar.
     * 
     * @param array $data Input data
     * @return array Data with calculated values
     */
    public function calculate(array $data): array
    {
        // Set default values if null
        $data['saldo_awal_unit'] = $data['saldo_awal_unit'] ?? 0;
        $data['saldo_awal_harga_satuan'] = $data['saldo_awal_harga_satuan'] ?? 0;
        $data['mutasi_tambah_unit'] = $data['mutasi_tambah_unit'] ?? 0;
        $data['mutasi_tambah_harga_satuan'] = $data['mutasi_tambah_harga_satuan'] ?? 0;
        $data['mutasi_kurang_unit'] = $data['mutasi_kurang_unit'] ?? 0;
        $data['mutasi_kurang_nilai_total'] = $data['mutasi_kurang_nilai_total'] ?? 0;

        // Perhitungan saldo_awal_total
        $data['saldo_awal_total'] = $this->calculateSaldoAwalTotal($data);

        // Perhitungan mutasi_tambah_nilai_total
        $data['mutasi_tambah_nilai_total'] = $this->calculateMutasiTambahTotal($data);

        // Perhitungan saldo_akhir_unit
        $data['saldo_akhir_unit'] = $this->calculateSaldoAkhirUnit($data);

        // Tentukan harga satuan untuk saldo akhir
        $hargaSatuanSaldoAkhir = $this->determineHargaSatuanSaldoAkhir($data);

        // Perhitungan saldo_akhir_total
        $data['saldo_akhir_total'] = $this->calculateSaldoAkhirTotal(
            $data['saldo_akhir_unit'],
            $hargaSatuanSaldoAkhir
        );

        // Auto-fill mutasi kurang nilai total jika ada unit kurang tapi nilai kosong
        if ($data['mutasi_kurang_unit'] > 0 && $data['mutasi_kurang_nilai_total'] == 0) {
            $data['mutasi_kurang_nilai_total'] = $data['mutasi_kurang_unit'] * $hargaSatuanSaldoAkhir;
        }

        return $data;
    }

    /**
     * Calculate saldo awal total.
     */
    protected function calculateSaldoAwalTotal(array $data): float
    {
        return $data['saldo_awal_unit'] * $data['saldo_awal_harga_satuan'];
    }

    /**
     * Calculate mutasi tambah nilai total.
     */
    protected function calculateMutasiTambahTotal(array $data): float
    {
        return $data['mutasi_tambah_unit'] * $data['mutasi_tambah_harga_satuan'];
    }

    /**
     * Calculate saldo akhir unit.
     */
    protected function calculateSaldoAkhirUnit(array $data): int
    {
        return $data['saldo_awal_unit'] + $data['mutasi_tambah_unit'] - $data['mutasi_kurang_unit'];
    }

    /**
     * Determine harga satuan for saldo akhir calculation.
     */
    protected function determineHargaSatuanSaldoAkhir(array $data): float
    {
        if ($data['saldo_awal_harga_satuan'] > 0) {
            return $data['saldo_awal_harga_satuan'];
        }

        if ($data['mutasi_tambah_harga_satuan'] > 0) {
            return $data['mutasi_tambah_harga_satuan'];
        }

        return 0;
    }

    /**
     * Calculate saldo akhir total.
     */
    protected function calculateSaldoAkhirTotal(int $saldoAkhirUnit, float $hargaSatuan): float
    {
        if ($saldoAkhirUnit > 0 && $hargaSatuan > 0) {
            return $saldoAkhirUnit * $hargaSatuan;
        }

        return 0;
    }
}
