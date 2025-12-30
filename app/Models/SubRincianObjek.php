<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubRincianObjek extends Model
{
    use HasFactory;

    protected $table = 'sub_rincian_objeks';
    protected $fillable = ['rincian_objek_id', 'kode', 'nama'];

    public function rincianObjek()
    {
        return $this->belongsTo(RincianObjek::class);
    }

    public function subSubRincianObjeks()
    {
        return $this->hasMany(SubSubRincianObjek::class);
    }
}