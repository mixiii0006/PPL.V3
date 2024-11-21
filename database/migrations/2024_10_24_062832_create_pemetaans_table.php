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
        Schema::create('pemetaans', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('dosen_id')->constrained('dosens')->cascadeOnDelete(); // Relasi ke dosens
            $table->foreignId('matakuliah_id')->constrained('mata_kuliahs')->cascadeOnDelete(); // Relasi ke matakuliah
            $table->string('nama_modul'); // Kolom untuk nama modul
            $table->string('hari'); // Kolom untuk hari
            $table->time('jam_mulai'); // Kolom untuk jam mulai
            $table->time('jam_selesai'); // Kolom untuk jam selesai
            $table->date('tanggal_mulai'); // Kolom untuk tanggal mulai
            $table->date('tanggal_selesai'); // Kolom untuk tanggal selesai
            $table->string('jenis_ruangan'); // Kolom untuk jenis ruangan (misal: RD/RK)
            $table->integer('jumlah_mahasiswa')->nullable(); // Kolom jumlah mahasiswa, nullable jika tidak memilih RD
            $table->timestamps(); // created_at dan updated_at
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemetaans');
    }
};
