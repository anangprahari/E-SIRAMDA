<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Objek;
use App\Models\Jenis;

class ObjekSeeder extends Seeder
{
    public function run(): void
    {
        //jenis 1.3.1 - 1.3.7
        $jenisTanah = Jenis::where('kode', '1.3.1')->first();
        $jenisPeralatandanMesin = Jenis::where('kode', '1.3.2')->first();
        $jenisGedungdanBangunan = Jenis::where('kode', '1.3.3')->first();
        $jenisJalanJaringandanIrigasi = Jenis::where('kode', '1.3.4')->first();
        $jenisAsetTetapLainnya = Jenis::where('kode', '1.3.5')->first();
        $jenisKontruksiDalamPekerjaan = Jenis::where('kode', '1.3.6')->first();
        $jenisAkumulasiPenyusutan = Jenis::where('kode', '1.3.7')->first();

        //jenis 1.5.2 - 1.5.6
        $jenisKemitraanDenganPihakKetiga = Jenis::where('kode', '1.5.2')->first();
        $jenisAsetTidakBerwujud = Jenis::where('kode', '1.5.3')->first();
        $jenisAsetLainlain = Jenis::where('kode', '1.5.4')->first();
        $jenisAkumulasiAmortisasiAsetTidakBerwujud = Jenis::where('kode', '1.5.5')->first();
        $jenisAkumulasiPenyusutanAsetLainnya = Jenis::where('kode', '1.5.6')->first();

        $objeks = [
            // 1.3.1
            ['jenis_id' => $jenisTanah->id, 'kode' => '1.3.1.01', 'nama' => 'TANAH'],

            // 1.3.2
            ['jenis_id' => $jenisPeralatandanMesin->id, 'kode' => '1.3.2.01', 'nama' => 'ALAT BERAT'],
            ['jenis_id' => $jenisPeralatandanMesin->id, 'kode' => '1.3.2.02', 'nama' => 'ALAT ANGKUTAN'],
            ['jenis_id' => $jenisPeralatandanMesin->id, 'kode' => '1.3.2.03', 'nama' => 'ALAT BENGKEL DAN ALAT UKUR'],
            ['jenis_id' => $jenisPeralatandanMesin->id, 'kode' => '1.3.2.04', 'nama' => 'ALAT PERTANIAN'],
            ['jenis_id' => $jenisPeralatandanMesin->id, 'kode' => '1.3.2.05', 'nama' => 'ALAT KANTOR DAN RUMAH TANGGA'],
            ['jenis_id' => $jenisPeralatandanMesin->id, 'kode' => '1.3.2.06', 'nama' => 'ALAT STUDIO, KOMUNIKASI DAN PEMANCAR'],
            ['jenis_id' => $jenisPeralatandanMesin->id, 'kode' => '1.3.2.07', 'nama' => 'ALAT KEDOKTERAN DAN KESEHATAN'],
            ['jenis_id' => $jenisPeralatandanMesin->id, 'kode' => '1.3.2.08', 'nama' => 'ALAT LABORATORIUM'],
            ['jenis_id' => $jenisPeralatandanMesin->id, 'kode' => '1.3.2.09', 'nama' => 'ALAT PERSENJATAAN'],
            ['jenis_id' => $jenisPeralatandanMesin->id, 'kode' => '1.3.2.10', 'nama' => 'KOMPUTER'],
            ['jenis_id' => $jenisPeralatandanMesin->id, 'kode' => '1.3.2.11', 'nama' => 'ALAT EKSPLORASI'],
            ['jenis_id' => $jenisPeralatandanMesin->id, 'kode' => '1.3.2.12', 'nama' => 'ALAT PENGEBORAN'],
            ['jenis_id' => $jenisPeralatandanMesin->id, 'kode' => '1.3.2.13', 'nama' => 'ALAT PRODUKSI, PENGOLAHAN DAN PEMURNIAN'],
            ['jenis_id' => $jenisPeralatandanMesin->id, 'kode' => '1.3.2.14', 'nama' => 'ALAT BANTU EKSPLORASI'],
            ['jenis_id' => $jenisPeralatandanMesin->id, 'kode' => '1.3.2.15', 'nama' => 'ALAT KESELAMATAN KERJA'],
            ['jenis_id' => $jenisPeralatandanMesin->id, 'kode' => '1.3.2.16', 'nama' => 'ALAT PERAGA'],
            ['jenis_id' => $jenisPeralatandanMesin->id, 'kode' => '1.3.2.17', 'nama' => 'PERALATAN PROSES/PRODUKSI'],
            ['jenis_id' => $jenisPeralatandanMesin->id, 'kode' => '1.3.2.18', 'nama' => 'RAMBU - RAMBU'],
            ['jenis_id' => $jenisPeralatandanMesin->id, 'kode' => '1.3.2.19', 'nama' => 'PERALATAN OLAH RAGA'],

            // 1.3.3
            ['jenis_id' => $jenisGedungdanBangunan->id, 'kode' => '1.3.3.01', 'nama' => 'BANGUNAN GEDUNG'],
            ['jenis_id' => $jenisGedungdanBangunan->id, 'kode' => '1.3.3.02', 'nama' => 'MONUMEN'],
            ['jenis_id' => $jenisGedungdanBangunan->id, 'kode' => '1.3.3.03', 'nama' => 'BANGUNAN MENARA'],
            ['jenis_id' => $jenisGedungdanBangunan->id, 'kode' => '1.3.3.04', 'nama' => 'TUGU TITIK KONTROL/PASTI'],

            // 1.3.4
            ['jenis_id' => $jenisJalanJaringandanIrigasi->id, 'kode' => '1.3.4.01', 'nama' => 'JALAN DAN JEMBATAN'],
            ['jenis_id' => $jenisJalanJaringandanIrigasi->id, 'kode' => '1.3.4.02', 'nama' => 'BANGUNAN AIR'],
            ['jenis_id' => $jenisJalanJaringandanIrigasi->id, 'kode' => '1.3.4.03', 'nama' => 'INSTALASI'],
            ['jenis_id' => $jenisJalanJaringandanIrigasi->id, 'kode' => '1.3.4.04', 'nama' => 'JARINGAN'],
            
            // 1.3.5
            ['jenis_id' => $jenisAsetTetapLainnya->id, 'kode' => '1.3.5.01', 'nama' => 'BAHAN PERPUSTAKAAN'],
            ['jenis_id' => $jenisAsetTetapLainnya->id, 'kode' => '1.3.5.02', 'nama' => 'BARANG BERCORAK KESENIAN/KEBUDAYAAN/OLAHRAGA'],
            ['jenis_id' => $jenisAsetTetapLainnya->id, 'kode' => '1.3.5.03', 'nama' => 'HEWAN'],
            ['jenis_id' => $jenisAsetTetapLainnya->id, 'kode' => '1.3.5.04', 'nama' => 'BIOTA PERAIRAN'],
            ['jenis_id' => $jenisAsetTetapLainnya->id, 'kode' => '1.3.5.05', 'nama' => 'TANAMAN'],
            ['jenis_id' => $jenisAsetTetapLainnya->id, 'kode' => '1.3.5.06', 'nama' => 'BARANG KOLEKSI NON BUDAYA'],
            ['jenis_id' => $jenisAsetTetapLainnya->id, 'kode' => '1.3.5.07', 'nama' => 'ASET TETAP DALAM RENOVASI'],

            // 1.3.6
            ['jenis_id' => $jenisKontruksiDalamPekerjaan->id, 'kode' => '1.3.6.01', 'nama' => 'KONSTRUKSI DALAM PENGERJAAN'],

            // 1.3.7
            ['jenis_id' => $jenisAkumulasiPenyusutan->id, 'kode' => '1.3.7.01', 'nama' => 'AKUMULASI PENYUSUTAN PERALATAN DAN MESIN'],
            ['jenis_id' => $jenisAkumulasiPenyusutan->id, 'kode' => '1.3.7.02', 'nama' => 'AKUMULASI PENYUSUTAN GEDUNG DAN BANGUNAN'],
            ['jenis_id' => $jenisAkumulasiPenyusutan->id, 'kode' => '1.3.7.03', 'nama' => 'AKUMULASI PENYUSUTAN JALAN, JARINGAN DAN IRIGASI'],
            ['jenis_id' => $jenisAkumulasiPenyusutan->id, 'kode' => '1.3.7.04', 'nama' => 'AKUMULASI PENYUSUTAN ASET TETAP LAINNYA'],

            // 1.5.2
            ['jenis_id' => $jenisKemitraanDenganPihakKetiga->id, 'kode' => '1.5.2.01', 'nama' => 'KEMITRAAN DENGAN PIHAK KETIGA'],

            // 1.5.3
            ['jenis_id' => $jenisAsetTidakBerwujud->id, 'kode' => '1.5.3.01', 'nama' => 'ASET TIDAK BERWUJUD'],

            // 1.5.4
            ['jenis_id' => $jenisAsetLainlain->id, 'kode' => '1.5.4.01', 'nama' => 'ASET LAIN-LAIN'],

            // 1.5.5
            ['jenis_id' => $jenisAkumulasiAmortisasiAsetTidakBerwujud->id, 'kode' => '1.5.5.01', 'nama' => 'AKUMULASI AMORTISASI ASET TIDAK BERWUJUD'],

            // 1.5.6
            ['jenis_id' => $jenisAkumulasiPenyusutanAsetLainnya->id, 'kode' => '1.5.6.01', 'nama' => 'AKUMULASI PENYUSUTAN ASET LAINNYA'],
        ];

        foreach ($objeks as $objek) {
            Objek::create($objek);
        }
    }
}
