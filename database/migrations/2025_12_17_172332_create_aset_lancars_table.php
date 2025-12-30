<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsetLancarsTable extends Migration
{
    public function up()
    {
        Schema::create('aset_lancars', function (Blueprint $table) {
            $table->id();

            $table->foreignId('rekening_uraian_id')->constrained('rekening_uraians')->onDelete('cascade');

            $table->string('nama_kegiatan')->nullable();
            $table->text('uraian_kegiatan')->nullable();
            $table->text('uraian_jenis_barang')->nullable();

            $table->integer('saldo_awal_unit');
            $table->decimal('saldo_awal_harga_satuan', 15, 2);
            $table->decimal('saldo_awal_total', 20, 2);

            $table->integer('mutasi_tambah_unit')->default(0);
            $table->decimal('mutasi_tambah_harga_satuan', 15, 2)->default(0);
            $table->decimal('mutasi_tambah_nilai_total', 20, 2)->default(0);

            $table->integer('mutasi_kurang_unit')->default(0);
            $table->decimal('mutasi_kurang_nilai_total', 20, 2)->default(0);

            $table->integer('saldo_akhir_unit')->default(0);
            $table->decimal('saldo_akhir_total', 20, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aset_lancars');
    }
}
