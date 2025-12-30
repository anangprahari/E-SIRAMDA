<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function subSubRincianObjek()
    {
        return $this->belongsTo(SubSubRincianObjek::class);
    }

    /**
     * Get all mutasi for the aset
     */
    public function mutasi()
    {
        return $this->hasMany(MutasiAset::class, 'aset_id');
    }

    // Accessor untuk mendapatkan URL file bukti barang
    public function getBuktiBarangUrlAttribute()
    {
        return $this->bukti_barang ? asset('storage/bukti_barang/' . $this->bukti_barang) : null;
    }

    // Accessor untuk mendapatkan URL file bukti berita
    public function getBuktiBeritaUrlAttribute()
    {
        return $this->bukti_berita ? asset('storage/bukti_berita/' . $this->bukti_berita) : null;
    }

    // Accessor untuk format harga
    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }

    // Accessor untuk total harga
    public function getTotalHargaAttribute()
    {
        return $this->jumlah_barang * $this->harga_satuan;
    }

    // Accessor untuk format total harga
    public function getFormattedTotalHargaAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }
}
