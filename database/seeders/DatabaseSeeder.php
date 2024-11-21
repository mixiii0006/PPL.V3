<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{


    public function run()
    {

        $dosens = [
            [
                'NIP' => 198711032014042001,
                'NIDN' => "0003118701",
                'Nama' => 'dr. Novriantika Lestari, M.Biomed.',
                'TTL' => 'Curup, 03-Nov-87',
                'CPNS' => '01-Apr-14',
                'Gol_Akhir' => 'III/d',
                'Pendidikan_Akhir' => 'S2/Sp1 UI',
                'Jabfung_Akhir' => 'Lektor',
                'Homebase_PDDIKTI' => 'S1 Kedokteran',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'NIP' => 198312252009121007,
                'NIDN' => "0025128301",
                'Nama' => 'dr. Zayadi Zainuddin, M.Pd.Ked., Sp.KFR',
                'TTL' => 'Rejang Lebong, 25-Dec-83',
                'CPNS' => '01-Dec-09',
                'Gol_Akhir' => 'III/d',
                'Pendidikan_Akhir' => 'S2/Sp1 UNPAD',
                'Jabfung_Akhir' => 'Lektor',
                'Homebase_PDDIKTI' => 'Pend. Profesi Dokter',
                'created_at' => now(),
                'updated_at' => now(),
            ],


        ];

        // Masukkan data ke tabel dosens
        DB::table('dosens')->insert($dosens);

        $ruangans = [
            [
                'nama_ruangan' => 'RK01',
                'kapasitas' => 70,
                'fasilitas' => 'LCD, Kursi Kuliah, Meja Dosen, AC,Kipas Angin, Kursi 1/2 biro hijau (kursi dosen), Papan tulis dinding, TV 29"',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RK02',
                'kapasitas' => 70,
                'fasilitas' => 'AC, Proyektor, Papan Tulis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RK03',
                'kapasitas' => 70,
                'fasilitas' => 'AC, Proyektor, Papan Tulis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RK04',
                'kapasitas' => 74,
                'fasilitas' => 'AC, Proyektor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'Seminar A',
                'kapasitas' => 50,
                'fasilitas' => 'AC, Proyektor, Papan Tulis, Mikrofon',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'Seminar B',
                'kapasitas' => 100,
                'fasilitas' => 'AC, Proyektor, Papan Tulis, Sound System',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_ruangan' => 'RD01',
                'kapasitas' => 20,
                'fasilitas' => 'AC, Proyektor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Anda dapat menambahkan lebih banyak data di sini
        ];

        // Masukkan data ke tabel ruangans
        DB::table('ruangans')->insert($ruangans);

        $mataKuliahs = [
            [
                'nama_matakuliah' => 'PSH-BK',
                'tingkat' => 1,
                'tanggal_mulai' => '2024-01-10',
                'tanggal_selesai' => '2024-05-15',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_matakuliah' => 'EBP3KH',
                'tingkat' => 1,
                'tanggal_mulai' => '2024-01-15',
                'tanggal_selesai' => '2024-05-20',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_matakuliah' => 'P2K2',
                'tingkat' => 1,
                'tanggal_mulai' => '2024-02-01',
                'tanggal_selesai' => '2024-06-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_matakuliah' => 'Riset 1',
                'tingkat' => 1,
                'tanggal_mulai' => '2024-02-15',
                'tanggal_selesai' => '2024-06-15',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_matakuliah' => 'Jaringan Komputer',
                'tingkat' => 3,
                'tanggal_mulai' => '2024-03-01',
                'tanggal_selesai' => '2024-07-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Tambahkan lebih banyak data di sini jika diperlukan
        ];

        // Masukkan data ke tabel mata_kuliahs
        DB::table('mata_kuliahs')->insert($mataKuliahs);
    }
}
