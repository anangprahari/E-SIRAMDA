<?php

namespace App\Repositories;

use App\Models\Aset;
use App\Models\MutasiAset;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class MutasiAsetRepository
{
    /**
     * Get paginated mutations with filters
     */
    public function getPaginatedWithFilters(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = MutasiAset::with(['aset', 'user'])
            ->latest('tanggal_mutasi');

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage)
            ->withQueryString()
            ->onEachSide(1);
    }

    /**
     * Get mutation by ID with relations
     */
    public function findWithRelations(int $id): MutasiAset
    {
        return MutasiAset::with(['aset', 'user'])->findOrFail($id);
    }

    /**
     * Get mutations for specific asset
     */
    public function getByAsset(Aset $aset, int $perPage = 4): LengthAwarePaginator
    {
        return $aset->mutasi()
            ->with(['user'])
            ->latest('tanggal_mutasi')
            ->paginate($perPage);
    }

    /**
     * Search assets for mutation (with room assigned)
     */
    public function searchAssets(?string $search, int $limit = 50): Collection
    {
        $query = Aset::whereNotNull('ruangan');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_jenis_barang', 'like', "%{$search}%")
                    ->orWhere('kode_barang', 'like', "%{$search}%")
                    ->orWhere('register', 'like', "%{$search}%");
            });
        }

        return $query->limit($limit)->get();
    }

    /**
     * Get assets available for mutation
     */
    public function getAvailableAssets(): Collection
    {
        return Aset::whereNotNull('ruangan')
            ->orderBy('kode_barang', 'asc')
            ->orderBy('register', 'asc')
            ->get([
                'id',
                'kode_barang',
                'nama_jenis_barang',
                'register',
                'ruangan'
            ]);
    }


    /**
     * Apply filters to query
     */
    private function applyFilters($query, array $filters): void
    {
        // Search
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                    ->orWhereHas('aset', function ($q) use ($search) {
                        $q->where('nama_jenis_barang', 'like', "%{$search}%")
                            ->orWhere('kode_barang', 'like', "%{$search}%")
                            ->orWhere('register', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by ruangan
        if (!empty($filters['ruangan'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('ruangan_asal', $filters['ruangan'])
                    ->orWhere('ruangan_tujuan', $filters['ruangan']);
            });
        }

        // Filter by date range
        if (!empty($filters['tanggal_dari'])) {
            $query->whereDate('tanggal_mutasi', '>=', $filters['tanggal_dari']);
        }
        if (!empty($filters['tanggal_sampai'])) {
            $query->whereDate('tanggal_mutasi', '<=', $filters['tanggal_sampai']);
        }
    }
}
