<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mataKuliahs = [
            [
                'nama_matakuliah' => 'PSH-BK',
                'tingkat' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_matakuliah' => 'EBP3KH',
                'tingkat' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_matakuliah' => 'P2K2',
                'tingkat' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_matakuliah' => 'Riset 1',
                'tingkat' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_matakuliah' => 'Tumbuh Kembang',
                'tingkat' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_matakuliah' => 'Kulit dan JP',
                'tingkat' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_matakuliah' => 'Muskuloskletal',
                'tingkat' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_matakuliah' => 'Metabolik Endokrin',
                'tingkat' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_matakuliah' => 'Reproduksi',
                'tingkat' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_matakuliah' => 'Saraf Jiwa',
                'tingkat' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_matakuliah' => 'Ilmu Kedokteran Komunitas (Kur. 2021)',
                'tingkat' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_matakuliah' => 'Etika dan Hukum (Kur. 2021)',
                'tingkat' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_matakuliah' => 'CPD(Kur. 2021)',
                'tingkat' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_matakuliah' => 'EBM(Kur. 2021)',
                'tingkat' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_matakuliah' => 'Manajemen Gawat Bencana (Kur. 2021)',
                'tingkat' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_matakuliah' => 'Ilmu Kedokteran Komunitas (Kur. 2015)',
                'tingkat' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_matakuliah' => 'Etika Kedokteran (Kur. 2015)',
                'tingkat' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_matakuliah' => 'Hukum Kedokteran (Kur. 2015)',
                'tingkat' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_matakuliah' => 'CPD(Kur. 2015)',
                'tingkat' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_matakuliah' => 'EBM(Kur. 2015)',
                'tingkat' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_matakuliah' => 'Manajemen Gawat Bencana (Kur. 2015)',
                'tingkat' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ];

        // Masukkan data ke tabel mata_kuliahs
        DB::table('mata_kuliahs')->insert($mataKuliahs);
    }
}
