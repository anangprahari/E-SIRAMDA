<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsetLancar extends Model
{
    use HasFactory;

    protected $fillable = [
        'rekening_uraian_id',
        'nama_kegiatan',
        'uraian_kegiatan',
        'uraian_jenis_barang',
        'saldo_awal_unit',
        'saldo_awal_harga_satuan',
        'saldo_awal_total',
        'mutasi_tambah_unit',
        'mutasi_tambah_harga_satuan',
        'mutasi_tambah_nilai_total',
        'mutasi_kurang_unit',
        'mutasi_kurang_nilai_total',
        'saldo_akhir_unit',
        'saldo_akhir_total',
    ];

    public function rekeningUraian()
    {
        return $this->belongsTo(RekeningUraian::class);
    }
}
