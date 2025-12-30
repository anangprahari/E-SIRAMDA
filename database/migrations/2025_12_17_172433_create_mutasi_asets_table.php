<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mutasi_asets', function (Blueprint $table) {
            $table->id();

            // Relasi ke aset
            $table->foreignId('aset_id')
                ->constrained('asets')
                ->cascadeOnDelete();

            // Relasi ke user (petugas yang melakukan mutasi)
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Informasi ruangan & lokasi
            $table->string('ruangan_asal');
            $table->string('lokasi_asal')->nullable();

            $table->string('ruangan_tujuan');
            $table->string('lokasi_tujuan')->nullable();

            // Informasi mutasi
            $table->date('tanggal_mutasi');
            $table->string('nomor_surat', 100)->unique();

            // Dokumen mutasi (WAJIB)
            $table->string('berita_acara_path');

            // Keterangan tambahan
            $table->text('keterangan')->nullable();

            $table->timestamps();

            // Index untuk performa query
            $table->index(['tanggal_mutasi', 'nomor_surat']);
            $table->index('ruangan_asal');
            $table->index('ruangan_tujuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_asets');
    }
};
