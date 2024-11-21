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
        Schema::create('jadwal_ruangans', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('pemetaan_id')->constrained('pemetaans')->cascadeOnDelete(); // FK ke pemetaans
            $table->foreignId('ruangan_id')->constrained('ruangans')->cascadeOnDelete(); // FK ke ruangans
            $table->timestamps(); // created_at dan updated_at

            $table->unique(['pemetaan_id', 'ruangan_id'], 'jadwal_ruangan_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_ruangans');
    }
};
