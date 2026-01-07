<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MutasiAset extends Model
{
    protected $table = 'mutasi_asets';

    protected $fillable = [
        'aset_id',
        'user_id',
        'ruangan_asal',
        'ruangan_tujuan',
        'lokasi_asal',  
        'lokasi_tujuan',    
        'tanggal_mutasi',
        'nomor_surat',
        'berita_acara_path',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_mutasi' => 'date',
    ];

    /**
     * Relasi ke aset
     */
    public function aset(): BelongsTo
    {
        return $this->belongsTo(Aset::class, 'aset_id');
    }

    /**
     * Relasi ke user yang melakukan mutasi
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get URL berita acara
     */
    public function getBeritaAcaraUrlAttribute(): ?string
    {
        return $this->berita_acara_path
            ? asset('storage/' . $this->berita_acara_path)
            : null;
    }
}
