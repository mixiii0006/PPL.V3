<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ruangans = [
            [
                'nama_ruangan' => 'RK01',
                'kapasitas' => 70,
                'fasilitas' => 'LCD, Kursi Kuliah, Meja Dosen, AC,Kipas Angin, Kursi 1/2 biro hijau (kursi dosen), Papan tulis dinding, TV 29"',
                'jenis_ruangan' => 'RK',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RK02',
                'kapasitas' => 70,
                'fasilitas' => 'LCD, Kursi Kuliah, Meja Dosen, AC,Kipas Angin, Kursi 1/2 biro hijau (kursi dosen), Papan tulis dinding, TV 29"',
                'jenis_ruangan' => 'RK',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RK03',
                'kapasitas' => 70,
                'fasilitas' => 'LCD, Kursi Kuliah, Meja Dosen, AC,Kipas Angin, Kursi 1/2 biro hijau (kursi dosen), Papan tulis tegak',
                'jenis_ruangan' => 'RK',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RK04',
                'kapasitas' => 74,
                'fasilitas' => 'LCD, Kursi Kuliah, Meja Dosen, AC,Kipas Angin, Kursi 1/2 biro hijau (kursi dosen), Papan tulis tegak',
                'jenis_ruangan' => 'RK',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RK SMT 1',
                'kapasitas' => 70,
                'fasilitas' => 'LCD, Kursi Kuliah, Meja Dosen, AC,Kipas Angin, Kursi 1/2 biro hijau (kursi dosen), Papan tulis tegak',
                'jenis_ruangan' => 'RK',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Ruangan Seminar
            [
                'nama_ruangan' => 'Seminar 1',
                'kapasitas' => 25,
                'fasilitas' => 'Papan Tulis Tegak, Kursi Kuliah Coklat, Kursi 1/2 Biro hijau, Meja Diskusi, AC',
                'jenis_ruangan' => 'Seminar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'Seminar 2',
                'kapasitas' => 25,
                'fasilitas' => 'Kursi kuliah Hijau, Meja diskusi, Kursi 1/2 biro hijau, Almari locker 6 pintu, AC, Kipas Angin, Papan Tulis',
                'jenis_ruangan' => 'Seminar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RD01',
                'kapasitas' => 12,
                'fasilitas' => 'Papan tulis dinding, Kursi kuliah, AC, Meja Diskusi, Kursi 1/2 biro hijau',
                'jenis_ruangan' => 'RD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RD02',
                'kapasitas' => 12,
                'fasilitas' => 'Papan tulis dinding, Kursi kuliah, AC, Meja Diskusi, Kursi 1/2 biro hijau',
                'jenis_ruangan' => 'RD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RD03',
                'kapasitas' => 12,
                'fasilitas' => 'Papan tulis dinding, Kursi kuliah, AC, Meja Diskusi, Kursi 1/2 biro hijau',
                'jenis_ruangan' => 'RD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RD04',
                'kapasitas' => 12,
                'fasilitas' => 'Papan tulis dinding, Kursi kuliah, AC, Meja Diskusi, Kursi 1/2 biro hijau',
                'jenis_ruangan' => 'RD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RD05',
                'kapasitas' => 12,
                'fasilitas' => 'Papan tulis dinding, Kursi kuliah, AC, Meja Diskusi, Kursi 1/2 biro hijau',
                'jenis_ruangan' => 'RD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RD06',
                'kapasitas' => 12,
                'fasilitas' => 'Papan tulis dinding, Kursi kuliah, AC, Meja Diskusi, Kursi 1/2 biro hijau',
                'jenis_ruangan' => 'RD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RD07',
                'kapasitas' => 13,
                'fasilitas' => 'Papan tulis tegak, Kursi kuliah, AC, Meja Diskusi, Kursi 1/2 biro hijau',
                'jenis_ruangan' => 'RD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RD08',
                'kapasitas' => 12,
                'fasilitas' => 'Papan tulis tegak, Kursi kuliah, AC, Meja Diskusi, Kursi 1/2 biro hijau',
                'jenis_ruangan' => 'RD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RD09',
                'kapasitas' => 12,
                'fasilitas' => 'Papan tulis tegak, Kursi kuliah, AC, Meja Diskusi, Kursi 1/2 biro hijau',
                'jenis_ruangan' => 'RD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RD10',
                'kapasitas' => 12,
                'fasilitas' => 'Papan tulis, Kursi kuliah, AC, Meja Diskusi, Kursi 1/2 biro hijau',
                'jenis_ruangan' => 'RD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RD11',
                'kapasitas' => 12,
                'fasilitas' => 'Papan tulis, Kursi kuliah, AC, Meja Diskusi, Kursi 1/2 biro hijau',
                'jenis_ruangan' => 'RD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RD12',
                'kapasitas' => 12,
                'fasilitas' => 'Papan tulis, Kursi kuliah, AC, Meja Diskusi, Kursi 1/2 biro hijau',
                'jenis_ruangan' => 'RD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RD13',
                'kapasitas' => 12,
                'fasilitas' => 'Papan tulis dinding, Kursi kuliah, AC, Meja Diskusi, Kursi 1/2 biro hijau',
                'jenis_ruangan' => 'RD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RD14',
                'kapasitas' => 12,
                'fasilitas' => 'Papan tulis dinding, Kursi kuliah, AC, Meja Diskusi, Kursi 1/2 biro hijau',
                'jenis_ruangan' => 'RD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RD15',
                'kapasitas' => 12,
                'fasilitas' => 'Papan tulis , Kursi kuliah, AC, Meja Diskusi, Kursi 1/2 biro hijau',
                'jenis_ruangan' => 'RD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RD16',
                'kapasitas' => 12,
                'fasilitas' => 'Papan tulis dinding, Kursi kuliah, AC, Meja Diskusi, Kursi 1/2 biro hijau',
                'jenis_ruangan' => 'RD',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RD17',
                'kapasitas' => 12,
                'fasilitas' => 'Papan tulis dinding, Kursi kuliah, AC, Meja Diskusi, Kursi 1/2 biro hijau',
                'jenis_ruangan' => 'RD',
                'created_at' => now(),
                'updated_at' => now(),
            ],


        ];

        // Masukkan data ke tabel ruangans
        DB::table('ruangans')->insert($ruangans);

    }
}
