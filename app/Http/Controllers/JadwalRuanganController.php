<?php

namespace App\Http\Controllers;

use App\Models\JadwalRuangan;
use App\Models\Pemetaan;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class JadwalRuanganController extends Controller
{
    public function index()
    {
        $datas = JadwalRuangan::with(['ruangan', 'pemetaan.mata_kuliah', 'pemetaan.dosen'])->get();
        $pemetaan = Pemetaan::with(['mata_kuliah', 'dosen'])->get();
        $ruangan = Ruangan::all();

        return view('jadwal_ruangan.index', compact('datas', 'pemetaan', 'ruangan'));
    }

    public function store(Request $request)
    {
        // Validasi data input
        $validated = $request->validate([
            'pemetaan_id' => 'required|exists:pemetaans,id',
            // 'ruangan_id' => 'required|exists:ruangans,id',
            'jam_masuk' => 'required',
            'jam_keluar' => 'required',
            'hari' => 'required',
            // 'jumlah_mahasiswa' => 'required' ,
            'jenis_ruangan' => 'required|in:RD,RK', // Opsi RD atau RK
        ]);
            if ($validated['jenis_ruangan'] === 'RD') {
                $validated['jumlah_mahasiswa'] = $request->validate([
                    'jumlah_mahasiswa' => 'required|integer|min:1', // Jika RD, jumlah_mahasiswa wajib diisi
                ])['jumlah_mahasiswa'];
            } else {
                // Jika RK dipilih, jumlah_mahasiswa tidak diperlukan
                $validated['jumlah_mahasiswa'] = null;
            }

        // // Validasi konflik jadwal
        // $isConflict = JadwalRuangan::where('hari', $validated['hari'])
        //     ->where('ruangan_id', $validated['ruangan_id'])
        //     ->where(function ($query) use ($validated) {
        //         $query->whereBetween('jam_masuk', [$validated['jam_masuk'], $validated['jam_keluar']])
        //             ->orWhereBetween('jam_keluar', [$validated['jam_masuk'], $validated['jam_keluar']])
        //             ->orWhere(function ($query) use ($validated) {
        //                 $query->where('jam_masuk', '<=', $validated['jam_masuk'])
        //                     ->where('jam_keluar', '>=', $validated['jam_keluar']); 
        //             });
        //     })
        //     ->exists();

        // if ($isConflict) {
        //     return redirect()->back()->withErrors(['conflict' => 'Ruangan sudah dipakai pada waktu tersebut.']);
        // }

        // Buat jadwal untuk jumlah ruangan yang diperlukan (hanya untuk RD)
        if ($validated['jenis_ruangan'] === 'RD') {
            $ruangan = Ruangan::where('jenis_ruangan', 'RD')->first();

            if (!$ruangan) {
                return redirect()->back()->withErrors(['ruangan_id' => 'Ruangan tidak valid untuk jenis RD.']);
            }

            $capacity = $ruangan->kapasitas;

            $neededRooms = ceil($validated['jumlah_mahasiswa'] / $capacity);


        //     dd([
        //         'validated' => $validated,
        //         'kapsitas' => $capacity,
        // 'available_ruangan' => Ruangan::where('jenis_ruangan', 'RD')->get(), // Menampilkan semua data ruangan
        //     ]);


            $availableRooms = Ruangan::where('jenis_ruangan', 'RD')
                ->whereNotIn('id', function ($query) use ($validated) {
                    $query->select('ruangan_id')
                        ->from('jadwal_ruangans')
                        ->where('hari', $validated['hari'])
                        ->where(function ($query) use ($validated) {
                            $query->where(function ($query) use ($validated) {
                                // Jadwal lain dimulai di dalam rentang waktu yang diajukan
                                $query->whereBetween('jam_masuk', [$validated['jam_masuk'], $validated['jam_keluar']]);
                            })->orWhere(function ($query) use ($validated) {
                                // Jadwal lain berakhir di dalam rentang waktu yang diajukan
                                $query->whereBetween('jam_keluar', [$validated['jam_masuk'], $validated['jam_keluar']]);
                            })->orWhere(function ($query) use ($validated) {
                                // Jadwal lain mencakup seluruh rentang waktu yang diajukan
                                $query->where('jam_masuk', '<=', $validated['jam_masuk'])
                                      ->where('jam_keluar', '>=', $validated['jam_keluar']);
                            });
                        });


                })
                ->take($neededRooms)
                ->get();

            if ($availableRooms->count() < $neededRooms) {
                return redirect()->back()->withErrors(['rooms' => 'Tidak cukup ruangan RD tersedia untuk jumlah mahasiswa.']);
            }

            foreach ($availableRooms as $room) {
                JadwalRuangan::create(array_merge($validated, ['ruangan_id' => $room->id]));
            }
        } else {
            // Logika untuk RK (Ruang Kuliah)
            $availableRooms = Ruangan::where('jenis_ruangan', 'RK')
                ->whereNotIn('id', function ($query) use ($validated) {
                    $query->select('ruangan_id')
                        ->from('jadwal_ruangans')
                        ->where('hari', $validated['hari'])
                        ->where(function ($query) use ($validated) {
                            $query->where(function ($query) use ($validated) {
                                // Jadwal lain dimulai di dalam rentang waktu yang diajukan
                                $query->whereBetween('jam_masuk', [$validated['jam_masuk'], $validated['jam_keluar']]);
                            })->orWhere(function ($query) use ($validated) {
                                // Jadwal lain berakhir di dalam rentang waktu yang diajukan
                                $query->whereBetween('jam_keluar', [$validated['jam_masuk'], $validated['jam_keluar']]);
                            })->orWhere(function ($query) use ($validated) {
                                // Jadwal lain mencakup seluruh rentang waktu yang diajukan
                                $query->where('jam_masuk', '<=', $validated['jam_masuk'])
                                      ->where('jam_keluar', '>=', $validated['jam_keluar']);
                            });
                        });

                })
                ->get(); // Tidak perlu `take`, karena hanya satu ruangan RK yang digunakan

            if ($availableRooms->count() < 1) {
                return redirect()->back()->withErrors(['rooms' => 'Tidak ada ruangan RK yang tersedia pada waktu tersebut.']);
            }

            // Simpan jadwal untuk RK
            JadwalRuangan::create(array_merge($validated, ['ruangan_id' => $availableRooms->first()->id]));
        }

        return redirect()->route('jadwal_ruangan.index')->with('success', 'Data jadwal berhasil dibuat!');
    }

    public function update(Request $request, string $id)
    {
        $datas = JadwalRuangan::findOrFail($id);

        $request->validate([
            'pemetaan_id' => 'required|exists:pemetaans,id',
            'ruangan_id' => 'required|exists:ruangans,id',
            'jam_masuk' => 'required',
            'jam_keluar' => 'required',
            'hari' => 'required',
        ]);

        $datas->update($request->all());

        return redirect(route('jadwal_ruangan.index'))->with('success', 'Data JadwalRuangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $datas = JadwalRuangan::findOrFail($id);
        $datas->delete();

        return redirect('/jadwal_ruangan')->with('success', 'Berhasil Dihapus');
    }

    public function filterJadwal(Request $request)
    {
        $selectedRuanganIds = $request->input('ruangan_ids', []);
        $selectedDay = $request->input('hari', null);

        $query = JadwalRuangan::query();

        if (!empty($selectedRuanganIds)) {
            $query->whereIn('ruangan_id', $selectedRuanganIds);
        }

        if ($selectedDay) {
            $query->where('hari', $selectedDay);
        }

        $datas = $query->get();
        $ruangan = Ruangan::all();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $pemetaan = Pemetaan::with(['mata_kuliah', 'dosen'])->get();

        return view('jadwal_ruangan.index', compact('ruangan', 'datas', 'pemetaan', 'days'));
    }
}
