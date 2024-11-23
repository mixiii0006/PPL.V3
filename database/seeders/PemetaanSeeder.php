<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\Pemetaan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PemetaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mendapatkan data dosen dan mata kuliah
        $dosen = Dosen::all(); // Ambil semua dosen
        $mataKuliah = MataKuliah::all(); // Ambil semua mata kuliah

        // Menambahkan beberapa pemetaan contoh
        foreach (range(1, 10) as $index) {
            Pemetaan::create([
                'dosen_id' => $dosen->random()->id,
                'matakuliah_id' => $mataKuliah->random()->id,
                'nama_modul' => 'Modul ' . Str::random(5),
                'hari' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'][array_rand(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'])],
                'jam_mulai' => Carbon::now()->addHours(rand(8, 15))->format('H:i'),
                'jam_selesai' => Carbon::now()->addHours(rand(16, 18))->format('H:i'),
                'tanggal_mulai' => Carbon::today()->format('Y-m-d'),
                'tanggal_selesai' => Carbon::today()->addDays(30)->format('Y-m-d'),
                'jenis_ruangan' => ['RK', 'RD', 'Seminar'][array_rand(['RK', 'RD', 'Seminar'])],
                'jumlah_mahasiswa' => rand(20, 100),
            ]);
        }
    }
}
