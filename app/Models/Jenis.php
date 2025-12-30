<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{
    use HasFactory;

    protected $table = 'jenis';
    protected $fillable = ['kelompok_id', 'kode', 'nama'];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function objeks()
    {
        return $this->hasMany(Objek::class);
    }
}
