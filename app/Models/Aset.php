<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class Aset extends Model
{
    use HasFactory;

    protected $table = 'asets';

    protected $fillable = [
        'sub_sub_rincian_objek_id',
        'kode_barang',
        'nama_bidang_barang',
        'register',
        'nama_jenis_barang',
        'merk_type',
        'no_sertifikat',
        'no_plat_kendaraan',
        'no_pabrik',
        'no_casis',
        'bahan',
        'asal_perolehan',
        'tahun_perolehan',
        'ukuran_barang_konstruksi',
        'satuan',
        'keadaan_barang',
        'jumlah_barang',
        'harga_satuan',
        'bukti_barang',
        'bukti_berita',
        'lokasi_barang',
        'keterangan',
        'ruangan',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'jumlah_barang' => 'integer',
    ];

    // ===================================
    // RELATIONSHIPS
    // ===================================

    public function subSubRincianObjek()
    {
        return $this->belongsTo(SubSubRincianObjek::class);
    }

    public function mutasi()
    {
        return $this->hasMany(MutasiAset::class, 'aset_id');
    }

    // ===================================
    // QUERY SCOPES
    // ===================================

    /**
     * Scope untuk ordering berdasarkan kode barang
     */
    public function scopeOrderByKodeBarang(Builder $query): Builder
    {
        return $query->orderByRaw('
            CAST(SUBSTRING_INDEX(kode_barang, ".", 1) AS UNSIGNED),
            CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_barang, ".", 2), ".", -1) AS UNSIGNED),
            CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_barang, ".", 3), ".", -1) AS UNSIGNED),
            CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_barang, ".", 4), ".", -1) AS UNSIGNED),  
            CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_barang, ".", 5), ".", -1) AS UNSIGNED),
            CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_barang, ".", 6), ".", -1) AS UNSIGNED),
            CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(kode_barang, ".", 7), ".", -1) AS UNSIGNED),
            CAST(SUBSTRING_INDEX(kode_barang, ".", -1) AS UNSIGNED)
        ');
    }

    /**
     * Scope untuk filter search
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('nama_bidang_barang', 'like', "%{$search}%")
                ->orWhere('nama_jenis_barang', 'like', "%{$search}%")
                ->orWhere('kode_barang', 'like', "%{$search}%")
                ->orWhere('register', 'like', "%{$search}%");
        });
    }

    /**
     * Scope untuk filter tahun perolehan
     */
    public function scopeFilterTahunPerolehan(Builder $query, ?int $tahun): Builder
    {
        if (empty($tahun)) {
            return $query;
        }

        return $query->where('tahun_perolehan', $tahun);
    }

    /**
     * Scope untuk filter keadaan barang
     */
    public function scopeFilterKeadaanBarang(Builder $query, ?string $keadaan): Builder
    {
        if (empty($keadaan)) {
            return $query;
        }

        return $query->where('keadaan_barang', $keadaan);
    }

    /**
     * Scope untuk filter ruangan
     */
    public function scopeFilterRuangan(Builder $query, ?string $ruangan): Builder
    {
        if (empty($ruangan)) {
            return $query;
        }

        return $query->where('ruangan', $ruangan);
    }

    // ===================================
    // ACCESSORS
    // ===================================

    public function getBuktiBarangUrlAttribute()
    {
        if (!$this->bukti_barang) {
            return null;
        }

        $path = 'bukti_barang/' . $this->bukti_barang;

        if (!Storage::disk('public')->exists($path)) {
            return null;
        }

        return asset('storage/' . $path);
    }

    public function getBuktiBeritaUrlAttribute()
    {
        if (!$this->bukti_berita) {
            return null;
        }

        $path = 'bukti_berita/' . $this->bukti_berita;

        if (!Storage::disk('public')->exists($path)) {
            return null;
        }

        return asset('storage/' . $path);
    }


    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }

    public function getTotalHargaAttribute()
    {
        return $this->jumlah_barang * $this->harga_satuan;
    }

    public function getFormattedTotalHargaAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }
}
