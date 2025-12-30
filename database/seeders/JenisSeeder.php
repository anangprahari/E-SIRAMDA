<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jenis;
use App\Models\Kelompok;

class JenisSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil kelompok yang ada
        $kelompokAsetTetap = Kelompok::where('kode', '1.3')->first();
        $kelompokAsetLainnya = Kelompok::where('kode', '1.5')->first();

        $jenis = [
            // Untuk ASET TETAP (1.3)
            ['kelompok_id' => $kelompokAsetTetap->id, 'kode' => '1.3.1', 'nama' => 'TANAH'],
            ['kelompok_id' => $kelompokAsetTetap->id, 'kode' => '1.3.2', 'nama' => 'PERALATAN DAN MESIN'],
            ['kelompok_id' => $kelompokAsetTetap->id, 'kode' => '1.3.3', 'nama' => 'GEDUNG DAN BANGUNAN'],
            ['kelompok_id' => $kelompokAsetTetap->id, 'kode' => '1.3.4', 'nama' => 'JALAN, JARINGAN DAN IRIGASI'],
            ['kelompok_id' => $kelompokAsetTetap->id, 'kode' => '1.3.5', 'nama' => 'ASET TETAP LAINNYA'],
            ['kelompok_id' => $kelompokAsetTetap->id, 'kode' => '1.3.6', 'nama' => 'KONTRUKSI DALAM PEKERJAAN'],
            ['kelompok_id' => $kelompokAsetTetap->id, 'kode' => '1.3.7', 'nama' => 'AKUMULASI PENYUSUTAN'],

            // Untuk ASET LAINNYA (1.5)
            ['kelompok_id' => $kelompokAsetLainnya->id, 'kode' => '1.5.2', 'nama' => 'KEMITRAAN DENGAN PIHAK KETIGA'],
            ['kelompok_id' => $kelompokAsetLainnya->id, 'kode' => '1.5.3', 'nama' => 'ASET TIDAK BERWUJUD'],
            ['kelompok_id' => $kelompokAsetLainnya->id, 'kode' => '1.5.4', 'nama' => 'ASET LAIN-LAIN'],
            ['kelompok_id' => $kelompokAsetLainnya->id, 'kode' => '1.5.5', 'nama' => 'AKUMULASI AMORTISASI ASET TIDAK BERWUJUD'],
            ['kelompok_id' => $kelompokAsetLainnya->id, 'kode' => '1.5.6', 'nama' => 'AKUMULASI PENYUSUTAN ASET LAINNYA'],
        ];

        foreach ($jenis as $item) {
            Jenis::create($item);
        }
    }
}
