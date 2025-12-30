<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningUraian extends Model
{
    use HasFactory;

    protected $fillable = ['kode_rekening', 'uraian'];

    public function asetLancars()
    {
        return $this->hasMany(AsetLancar::class);
    }
}
