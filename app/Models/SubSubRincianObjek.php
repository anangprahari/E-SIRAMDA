<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubSubRincianObjek extends Model
{
    use HasFactory;

    protected $table = 'sub_sub_rincian_objeks';
    protected $fillable = ['sub_rincian_objek_id', 'kode', 'nama_barang'];

    public function subRincianObjek()
    {
        return $this->belongsTo(SubRincianObjek::class);
    }

    public function asets()
    {
        return $this->hasMany(Aset::class);
    }
}
