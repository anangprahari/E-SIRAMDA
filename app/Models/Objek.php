<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objek extends Model
{
    use HasFactory;

    protected $table = 'objeks';
    protected $fillable = ['jenis_id', 'kode', 'nama'];

    public function jenis()
    {
        return $this->belongsTo(Jenis::class);
    }

    public function rincianObjeks()
    {
        return $this->hasMany(RincianObjek::class);
    }
}
