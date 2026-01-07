<?php

namespace App\Repositories;

use App\Models\AsetLancar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AsetLancarRepository
{
    /**
     * Get filtered and paginated AsetLancar data.
     */
    public function getFiltered(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->buildFilteredQuery($filters);

        return $query
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString()
            ->onEachSide(1);
    }

    /**
     * Get all filtered AsetLancar data without pagination.
     */
    public function getAllFiltered(array $filters): Collection
    {
        $query = $this->buildFilteredQuery($filters);

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Build filtered query based on request filters.
     */
    protected function buildFilteredQuery(array $filters): Builder
    {
        $query = AsetLancar::with('rekeningUraian');

        // Search functionality
        if (!empty($filters['search'])) {
            $this->applySearchFilter($query, $filters['search']);
        }

        // Filter by rekening_uraian_id
        if (!empty($filters['rekening_uraian_id'])) {
            $query->where('rekening_uraian_id', $filters['rekening_uraian_id']);
        }

        // Filter by nama_kegiatan
        if (!empty($filters['nama_kegiatan'])) {
            $query->where('nama_kegiatan', 'like', "%{$filters['nama_kegiatan']}%");
        }

        // Filter by uraian_jenis_barang
        if (!empty($filters['uraian_jenis_barang'])) {
            $query->where('uraian_jenis_barang', 'like', "%{$filters['uraian_jenis_barang']}%");
        }

        // Filter by saldo_awal range
        $this->applyRangeFilter($query, 'saldo_awal_total', $filters, 'saldo_awal_min', 'saldo_awal_max');

        // Filter by mutasi_tambah range
        $this->applyRangeFilter($query, 'mutasi_tambah_nilai_total', $filters, 'mutasi_tambah_min', 'mutasi_tambah_max');

        // Filter by saldo_akhir range
        $this->applyRangeFilter($query, 'saldo_akhir_total', $filters, 'saldo_akhir_min', 'saldo_akhir_max');

        // Filter by date range
        $this->applyDateRangeFilter($query, $filters);

        return $query;
    }

    /**
     * Apply search filter to query.
     */
    protected function applySearchFilter(Builder $query, string $search): void
    {
        $query->where(function ($q) use ($search) {
            $q->where('nama_kegiatan', 'like', "%{$search}%")
                ->orWhere('uraian_kegiatan', 'like', "%{$search}%")
                ->orWhere('uraian_jenis_barang', 'like', "%{$search}%")
                ->orWhereHas('rekeningUraian', function ($q) use ($search) {
                    $q->where('kode_rekening', 'like', "%{$search}%")
                        ->orWhere('uraian', 'like', "%{$search}%");
                });
        });
    }

    /**
     * Apply range filter to query.
     */
    protected function applyRangeFilter(
        Builder $query,
        string $field,
        array $filters,
        string $minKey,
        string $maxKey
    ): void {
        if (!empty($filters[$minKey])) {
            $query->where($field, '>=', $filters[$minKey]);
        }

        if (!empty($filters[$maxKey])) {
            $query->where($field, '<=', $filters[$maxKey]);
        }
    }

    /**
     * Apply date range filter to query.
     */
    protected function applyDateRangeFilter(Builder $query, array $filters): void
    {
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
    }

    /**
     * Create new AsetLancar record.
     */
    public function create(array $data): AsetLancar
    {
        return AsetLancar::create($data);
    }

    /**
     * Update existing AsetLancar record.
     */
    public function update(AsetLancar $asetLancar, array $data): bool
    {
        return $asetLancar->update($data);
    }

    /**
     * Delete AsetLancar record.
     */
    public function delete(AsetLancar $asetLancar): bool
    {
        return $asetLancar->delete();
    }

    /**
     * Find AsetLancar by ID with relations.
     */
    public function findWithRelations(int $id): ?AsetLancar
    {
        return AsetLancar::with('rekeningUraian')->find($id);
    }
}
