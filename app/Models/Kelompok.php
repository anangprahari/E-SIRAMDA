<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $table = 'kelompoks';
    protected $fillable = ['akun_id', 'kode', 'nama'];

    public function akun()
    {
        return $this->belongsTo(Akun::class);
    }

    public function jenis()
    {
        return $this->hasMany(Jenis::class);
    }
}
