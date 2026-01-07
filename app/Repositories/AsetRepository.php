<?php

namespace App\Repositories;

use App\Models\Aset;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AsetRepository
{
    /**
     * Get filtered and paginated asets
     */
    public function getFilteredPaginated(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->getFilteredQuery($filters);

        return $query->orderByKodeBarang()
            ->paginate($perPage)
            ->withQueryString()
            ->onEachSide(1);
    }

    /**
     * Get filtered query (reusable for export)
     */
    public function getFilteredQuery(array $filters): Builder
    {
        return Aset::query()
            ->with([
                'subSubRincianObjek.subRincianObjek.rincianObjek.objek.jenis.kelompok.akun'
            ])
            ->search($filters['search'] ?? null)
            ->filterTahunPerolehan($filters['tahun_perolehan'] ?? null)
            ->filterKeadaanBarang($filters['keadaan_barang'] ?? null)
            ->filterRuangan($filters['ruangan'] ?? null)
            ->when(
                !empty($filters['tahun_dari']) || !empty($filters['tahun_sampai']),
                function ($query) use ($filters) {
                    $query->where(function ($q) use ($filters) {
                        if (!empty($filters['tahun_dari']) && !empty($filters['tahun_sampai'])) {
                            $q->whereBetween('tahun_perolehan', [
                                $filters['tahun_dari'],
                                $filters['tahun_sampai']
                            ]);
                        } elseif (!empty($filters['tahun_dari'])) {
                            $q->where('tahun_perolehan', '>=', $filters['tahun_dari']);
                        } elseif (!empty($filters['tahun_sampai'])) {
                            $q->where('tahun_perolehan', '<=', $filters['tahun_sampai']);
                        }
                    });
                }
            );
    }

    /**
     * Find aset by ID with relations
     */
    public function findWithRelations(int $id): ?Aset
    {
        return Aset::with([
            'subSubRincianObjek.subRincianObjek.rincianObjek.objek.jenis.kelompok.akun'
        ])->find($id);
    }

    /**
     * Get last register number for a kode_barang
     */
    public function getLastRegisterNumber(string $kodeBarang): int
    {
        $lastAsset = Aset::where('kode_barang', $kodeBarang)
            ->orderByRaw('CAST(register AS UNSIGNED) DESC')
            ->first();

        if (!$lastAsset) {
            return 0;
        }

        return (int) $lastAsset->register;
    }

    /**
     * Check if kode_barang and register combination exists
     */
    public function existsByKodeBarangAndRegister(string $kodeBarang, string $register, ?int $excludeId = null): bool
    {
        $query = Aset::where('kode_barang', $kodeBarang)
            ->where('register', $register);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Create aset
     */
    public function create(array $data): Aset
    {
        return Aset::create($data);
    }

    /**
     * Update aset
     */
    public function update(Aset $aset, array $data): bool
    {
        return $aset->update($data);
    }

    /**
     * Delete aset
     */
    public function delete(Aset $aset): bool
    {
        return $aset->delete();
    }

    /**
     * Count asets by kode_barang
     */
    public function countByKodeBarang(string $kodeBarang): int
    {
        return Aset::where('kode_barang', $kodeBarang)->count();
    }
}
