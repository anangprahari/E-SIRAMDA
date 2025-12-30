<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    use HasFactory;

    protected $table = 'akuns';
    protected $fillable = ['kode', 'nama'];

    public function kelompoks()
    {
        return $this->hasMany(Kelompok::class);
    }
}
