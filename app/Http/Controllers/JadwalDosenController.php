<?php

namespace App\Http\Controllers;

use App\Models\JadwalRuangan;
use App\Models\MataKuliah;
use App\Models\Pemetaan;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalDosenController extends Controller
{
    public function index()
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Ambil data jadwal berdasarkan kondisi
        if ($user->email === $user->nip) {
            // Jika email sama dengan NIP, tampilkan jadwal sesuai dengan NIP dosen
            $datas = JadwalRuangan::whereHas('pemetaan.dosen', function ($query) use ($user) {
                $query->where('nip', $user->nip);
            })->get();
        } else {
            // Jika bukan dosen, tampilkan seluruh jadwal
            $datas = JadwalRuangan::with(['pemetaan', 'ruangan'])->get();
        }

        // Ambil data tambahan untuk view
        $ruangan = Ruangan::all();
        $matakuliah = MataKuliah::all();
        $pemetaan = Pemetaan::with(['mata_kuliah', 'dosen'])->get();

        // Return data ke view
        return view('jadwal_dosen.index', compact('datas', 'ruangan', 'matakuliah', 'pemetaan'));
    }


    public function show($day)
    {
        // Ambil data jadwal berdasarkan hari
        $datas = JadwalRuangan::with(['ruangan', 'pemetaan.mata_kuliah', 'pemetaan.dosen'])
            ->whereHas('pemetaan', function ($query) use ($day) {
                $query->where('hari', $day);
            })
            ->get();
        $pemetaan = Pemetaan::with(['mata_kuliah', 'dosen'])->get();


        $ruangan = Ruangan::all();
        $matakuliah = MataKuliah::all();


        // Tampilkan view dengan data jadwal yang sesuai
        return view('jadwal_dosen.index', compact('datas', 'day','pemetaan', 'matakuliah','ruangan'));
    }
    public function filterJadwal(Request $request)
    {
        // Ambil data dari input request
        $selectedRuanganIds = $request->input('ruangan_ids', []); // Filter ID ruangan
        $selectedMataKuliahIds = $request->input('mata_kuliah_ids', []); // Filter ID mata kuliah
        $selectedHari = $request->input('hari'); // Filter hari

        // Mulai query jadwal ruangan
        $query = JadwalRuangan::query();

        // Filter berdasarkan ID ruangan yang dipilih
        if (!empty($selectedRuanganIds)) {
            $query->whereIn('ruangan_id', $selectedRuanganIds);
        }

        // Gabungkan jadwal dengan tabel pemetaan dan mata kuliah
        $query->whereHas('pemetaan', function ($pemetaanQuery) use ($selectedMataKuliahIds, $selectedHari) {
            // Filter hanya jadwal yang masih berlangsung
            $pemetaanQuery->whereDate('tanggal_mulai', '<=', now())
                        ->whereDate('tanggal_selesai', '>=', now());

            // Filter berdasarkan hari
            if (!empty($selectedHari)) {
                $pemetaanQuery->where('hari', $selectedHari);
            }

            // Filter berdasarkan ID mata kuliah (dari relasi mata_kuliah)
            if (!empty($selectedMataKuliahIds)) {
                $pemetaanQuery->whereHas('mata_kuliah', function ($mataKuliahQuery) use ($selectedMataKuliahIds) {
                    $mataKuliahQuery->whereIn('id', $selectedMataKuliahIds);
                });
            }
        });

        // Ambil data jadwal yang sudah difilter
        $datas = $query->get();

        // Ambil daftar ruangan untuk form filter
        $ruangan = Ruangan::all();

        // Ambil daftar mata kuliah untuk form filter
        $matakuliah = MataKuliah::all();

        // Ambil seluruh data pemetaan dengan relasi mata_kuliah dan dosen
        $pemetaan = Pemetaan::with(['mata_kuliah', 'dosen'])->get();

        // Kembalikan view dengan data jadwal yang sudah difilter
        return view('jadwal_dosen.index', compact('ruangan', 'datas', 'matakuliah', 'pemetaan'));
    }



}
