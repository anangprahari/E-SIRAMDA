<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelompok;
use App\Models\Akun;

class KelompokSeeder extends Seeder
{
    public function run(): void
    {
        $akunAset = Akun::where('kode', '1')->first();

        $kelompoks = [
            ['akun_id' => $akunAset->id, 'kode' => '1.3', 'nama' => 'ASET TETAP'],
            ['akun_id' => $akunAset->id, 'kode' => '1.5', 'nama' => 'ASET LAINNYA'],
        ];

        foreach ($kelompoks as $kelompok) {
            Kelompok::create($kelompok);
        }
    }
}
