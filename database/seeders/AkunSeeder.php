<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Akun;

class AkunSeeder extends Seeder
{
    public function run(): void
    {
        $akuns = [
            ['kode' => '1', 'nama' => 'ASET'],
        ];

        foreach ($akuns as $akun) {
            Akun::create($akun);
        }
    }
}
