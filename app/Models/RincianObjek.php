<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RincianObjek extends Model
{
    use HasFactory;

    protected $table = 'rincian_objeks';
    protected $fillable = ['objek_id', 'kode', 'nama'];

    public function objek()
    {
        return $this->belongsTo(Objek::class);
    }

    public function subRincianObjeks()
    {
        return $this->hasMany(SubRincianObjek::class);
    }
}
