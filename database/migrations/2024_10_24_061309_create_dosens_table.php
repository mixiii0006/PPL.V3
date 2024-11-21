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
        Schema::create('dosens', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->integer('NIP')->unique(); // Assuming NIP is unique
            $table->string('NIDN')->nullable();
            $table->string('Nama', 100); // Adjust length if needed
            $table->string('TTL')->nullable(); // Assuming it's a date field
            $table->string('CPNS')->nullable(); // Date of first employment
            $table->string('Gol_Akhir', 50)->nullable(); // Final rank, adjust length
            $table->string('Pendidikan_Akhir', 50)->nullable(); // Final education
            $table->string('Jabfung_Akhir', 50)->nullable(); // Final functional position
            $table->string('Homebase_PDDIKTI', 100)->nullable(); // Homebase info
            $table->timestamps(); // Created at and Updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosens');
    }
};
