<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\JadwalRuangan;
use App\Models\MataKuliah;
use App\Models\Pemetaan;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class JadwalRuanganController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');


        JadwalRuangan::whereIn('pemetaan_id', function ($query) {
            $query->select('id')
                ->from('pemetaans')
                ->whereDate('tanggal_mulai', '<=', now()) // Jadwal sudah mulai
                ->whereDate('tanggal_selesai', '>=', now()); // Jadwal belum selesai
        })->get();

        $user = Auth::user();

        // Ambil data jadwal berdasarkan login user
        $datas = JadwalRuangan::with(['ruangan', 'pemetaan.mata_kuliah', 'pemetaan.dosen'])
            ->whereHas('pemetaan', function ($query) {
                $query->whereDate('tanggal_mulai', '<=', now())
                    ->whereDate('tanggal_selesai', '>=', now());
            })
            ->when($user->email, function ($query) use ($user) {
                // Periksa apakah ada dosen dengan NIP sesuai email
                $hasDosen = Dosen::where('NIP', $user->email)->exists();

                if ($hasDosen) {
                    // Jika email cocok dengan NIP dosen, filter berdasarkan NIP
                    $query->whereHas('pemetaan.dosen', function ($q) use ($user) {
                        $q->where('nip', $user->email);
                    });
                }
                // Jika tidak cocok, jangan tambahkan filter, tampilkan semua jadwal
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('ruangan', function ($q) use ($search) {
                        $q->where('nama_ruangan', 'like', "%{$search}%");
                    })
                    ->orWhereHas('pemetaan.mata_kuliah', function ($q) use ($search) {
                        $q->where('nama_matakuliah', 'like', "%{$search}%");
                    })
                    ->orWhereHas('pemetaan.dosen', function ($q) use ($search) {
                        $q->where('Nama', 'like', "%{$search}%");
                    });
                });
            })
            ->get();

        // Ambil seluruh data pemetaan dengan relasi mata_kuliah dan dosen
        $pemetaan = Pemetaan::with(['mata_kuliah', 'dosen'])->get();

        // Ambil seluruh data ruangan
        $ruangan = Ruangan::all();

        // Ambil seluruh data mata kuliah
        $matakuliah = MataKuliah::all();

        $dosen = Dosen::all();

        // Kembalikan view dengan data yang dibutuhkan
        return view('jadwal_ruangan.index', compact('datas', 'pemetaan', 'ruangan', 'matakuliah', 'dosen'));
    }


    // public function store(Request $request)
    // {
    //     // Validate input data, only pemetaan_id is required from the user
    //     $validated = $request->validate([
    //         'pemetaan_id' => 'required|exists:pemetaans,id', // Only pemetaan_id is needed from the user
    //     ]);

    //     // Fetch the pemetaan data using the pemetaan_id
    //     $pemetaan = Pemetaan::findOrFail($validated['pemetaan_id']);

    //     // Extract the required values from the pemetaan data
    //     $jam_masuk = $pemetaan->jam_masuk;
    //     $jam_keluar = $pemetaan->jam_keluar;
    //     $hari = $pemetaan->hari;
    //     $jenis_ruangan = $pemetaan->jenis_ruangan;

    //     // Additional validation for 'jumlah_mahasiswa' if the room type is RD
    //     if ($jenis_ruangan === 'RD') {
    //         $validated['jumlah_mahasiswa'] = $request->validate([
    //             'jumlah_mahasiswa' => 'required|integer|min:1', // Must be provided for RD
    //         ])['jumlah_mahasiswa'];
    //     } else {
    //         // For RK and Seminar, no 'jumlah_mahasiswa' is required
    //         $validated['jumlah_mahasiswa'] = null;
    //     }

    //     // Handle Room Assignment based on Room Type
    //     if ($jenis_ruangan === 'RD') {
    //         // Get a room with RD type and check if the number of students exceeds capacity
    //         $ruangan = Ruangan::where('jenis_ruangan', 'RD')->first();

    //         if (!$ruangan) {
    //             return redirect()->back()->withErrors(['ruangan_id' => 'No valid RD rooms available.']);
    //         }

    //         $capacity = $ruangan->kapasitas;

    //         // Calculate the required number of rooms based on student count
    //         $neededRooms = ceil($validated['jumlah_mahasiswa'] / $capacity);

    //         // Retrieve available rooms for RD, ensuring there are no conflicts with the schedule
    //         $availableRooms = Ruangan::where('jenis_ruangan', 'RD')
    //             ->whereNotIn('id', function ($query) use ($jam_masuk, $jam_keluar, $hari) {
    //                 $query->select('ruangan_id')
    //                     ->from('jadwal_ruangans')
    //                     ->where('hari', $hari)
    //                     ->where(function ($query) use ($jam_masuk, $jam_keluar) {
    //                         $query->whereBetween('jam_masuk', [$jam_masuk, $jam_keluar])
    //                             ->orWhereBetween('jam_keluar', [$jam_masuk, $jam_keluar])
    //                             ->orWhere(function ($query) use ($jam_masuk, $jam_keluar) {
    //                                 $query->where('jam_masuk', '<=', $jam_masuk)
    //                                     ->where('jam_keluar', '>=', $jam_keluar);
    //                             });
    //                     });
    //             })
    //             ->take($neededRooms) // Limit to the number of needed rooms
    //             ->get();

    //         // Check if there are enough available rooms
    //         if ($availableRooms->count() < $neededRooms) {
    //             return redirect()->back()->withErrors(['rooms' => 'Not enough RD rooms available for the number of students.']);
    //         }

    //         // Assign the available rooms for RD
    //         foreach ($availableRooms as $room) {
    //             JadwalRuangan::create(array_merge($validated, ['ruangan_id' => $room->id]));
    //         }
    //     } elseif ($jenis_ruangan === 'RK' || $jenis_ruangan === 'Seminar') {
    //         // For RK and Seminar, we only need one available room
    //         $roomType = $jenis_ruangan; // Can be 'RK' or 'Seminar'

    //         // Retrieve available rooms for RK or Seminar, ensuring there are no conflicts with the schedule
    //         $availableRooms = Ruangan::where('jenis_ruangan', $roomType)
    //             ->whereNotIn('id', function ($query) use ($jam_masuk, $jam_keluar, $hari) {
    //                 $query->select('ruangan_id')
    //                     ->from('jadwal_ruangans')
    //                     ->where('hari', $hari)
    //                     ->where(function ($query) use ($jam_masuk, $jam_keluar) {
    //                         $query->whereBetween('jam_masuk', [$jam_masuk, $jam_keluar])
    //                             ->orWhereBetween('jam_keluar', [$jam_masuk, $jam_keluar])
    //                             ->orWhere(function ($query) use ($jam_masuk, $jam_keluar) {
    //                                 $query->where('jam_masuk', '<=', $jam_masuk)
    //                                     ->where('jam_keluar', '>=', $jam_keluar);
    //                             });
    //                     });
    //             })
    //             ->get(); // Only one room is needed for RK or Seminar

    //         // Check if there is at least one available room
    //         if ($availableRooms->count() < 1) {
    //             return redirect()->back()->withErrors(['rooms' => "No $roomType rooms available for the specified time."]);
    //         }

    //         // Assign the available room for RK or Seminar
    //         JadwalRuangan::create(array_merge($validated, ['ruangan_id' => $availableRooms->first()->id]));
    //     }

    //     // Redirect to the index page with success message
    //     return redirect()->route('jadwal_ruangan.index')->with('success', 'Schedule created successfully!');
    // }


    // public function update(Request $request, string $id)
    // {

    //     $datas = JadwalRuangan::findOrFail($id);

    //     $request->validate([
    //             'pemetaan_id' => 'required|exists:pemetaans,id',
    //             'ruangan_id' => 'required|exists:ruangans,id',
    //     ]);

    //     $datas->update($request->all());
    //     return redirect(route('jadwal_ruangan.index'))->with('success', 'Data JadwalRuangan berhasil diperbarui.');
    // }


    // public function delete(JadwalRuangan $datas)
    // {

    //     return view('jadwal_ruangan.hapus', compact('datas'));
    // }
    // public function destroy($id){
    //     $datas = JadwalRuangan::findorfail($id);
    //     $datas->delete();
    //     return redirect('/jadwal_ruangan')->with('success', 'Berhasil Dihapus');
    // }

    public function show($day)
    {
        $user = Auth::user();
        // Ambil data jadwal berdasarkan hari
        $datas = JadwalRuangan::with(['ruangan', 'pemetaan.mata_kuliah', 'pemetaan.dosen'])
            ->whereHas('pemetaan', function ($query) {
                $query->whereDate('tanggal_mulai', '<=', now())
                    ->whereDate('tanggal_selesai', '>=', now());
            })
            ->when($user->email, function ($query) use ($user) {
                // Periksa apakah ada dosen dengan NIP sesuai email
                $hasDosen = Dosen::where('NIP', $user->email)->exists();

                if ($hasDosen) {
                    // Jika email cocok dengan NIP dosen, filter berdasarkan NIP
                    $query->whereHas('pemetaan.dosen', function ($q) use ($user) {
                        $q->where('nip', $user->email);
                    });
                }
                // Jika tidak cocok, jangan tambahkan filter, tampilkan semua jadwal
            })
            ->whereHas('pemetaan', function ($query) use ($day) {
                $query->where('hari', $day);
            })
            ->get();
        $pemetaan = Pemetaan::with(['mata_kuliah', 'dosen'])->get();


        $ruangan = Ruangan::all();
        $matakuliah = MataKuliah::all();
        $dosen = Dosen::all();


        // Tampilkan view dengan data jadwal yang sesuai
        return view('jadwal_ruangan.index', compact('dosen','datas', 'day','pemetaan', 'matakuliah','ruangan'));
    }
//     public function filterJadwal(Request $request)
// {
//     // Ambil data dari input request
//     $selectedRuanganIds = $request->input('ruangan_ids', []); // Filter ID ruangan
//     $selectedMataKuliahIds = $request->input('mata_kuliah_ids', []); // Filter ID mata kuliah
//     $selectedHari = $request->input('hari'); // Filter hari

//     // Mulai query jadwal ruangan
//     $query = JadwalRuangan::query();

//     // Ambil user yang sedang login
//     $user = Auth::user();

//     // Tambahkan filter berdasarkan email (untuk cek NIP dosen)
//     $query->when($user->email, function ($query) use ($user) {
//         // Periksa apakah ada dosen dengan NIP sesuai email
//         $hasDosen = Dosen::where('NIP', $user->email)->exists();

//         if ($hasDosen) {
//             // Jika email cocok dengan NIP dosen, filter berdasarkan NIP
//             $query->whereHas('pemetaan.dosen', function ($q) use ($user) {
//                 $q->where('nip', $user->email);
//             });
//         }
//         // Jika tidak cocok, jangan tambahkan filter, tampilkan semua jadwal
//     });

//     // Filter berdasarkan ID ruangan yang dipilih
//     if (!empty($selectedRuanganIds)) {
//         $query->whereIn('ruangan_id', $selectedRuanganIds);
//     }

//     // Gabungkan jadwal dengan tabel pemetaan dan mata kuliah
//     $query->whereHas('pemetaan', function ($pemetaanQuery) use ($selectedMataKuliahIds, $selectedHari) {
//         // Filter hanya jadwal yang masih berlangsung
//         $pemetaanQuery->whereDate('tanggal_mulai', '<=', now())
//                     ->whereDate('tanggal_selesai', '>=', now());

//         // Filter berdasarkan hari
//         if (!empty($selectedHari)) {
//             $pemetaanQuery->where('hari', $selectedHari);
//         }

//         // Filter berdasarkan ID mata kuliah (dari relasi mata_kuliah)
//         if (!empty($selectedMataKuliahIds)) {
//             $pemetaanQuery->whereHas('mata_kuliah', function ($mataKuliahQuery) use ($selectedMataKuliahIds) {
//                 $mataKuliahQuery->whereIn('id', $selectedMataKuliahIds);
//             });
//         }
//     });

//     // Ambil data jadwal yang sudah difilter
//     $datas = $query->get();

//     // Ambil daftar ruangan untuk form filter
//     $ruangan = Ruangan::all();

//     // Ambil daftar mata kuliah untuk form filter
//     $matakuliah = MataKuliah::all();

//     // Ambil seluruh data pemetaan dengan relasi mata_kuliah dan dosen
//     $pemetaan = Pemetaan::with(['mata_kuliah', 'dosen'])->get();
//     $dosen = Dosen::all();

//     // Kembalikan view dengan data jadwal yang sudah difilter
//     return view('jadwal_ruangan.index', compact('ruangan', 'datas', 'matakuliah', 'pemetaan','dosen'));
// }

public function filterJadwal(Request $request)
{
    // Ambil data dari input request
    $selectedRuanganIds = $request->input('ruangan_ids', []); // Filter ID ruangan
    $selectedMataKuliahIds = $request->input('mata_kuliah_ids', []); // Filter ID mata kuliah
    $selectedHari = $request->input('hari'); // Filter hari

    // Mulai query jadwal ruangan
    $query = JadwalRuangan::query();

    // Ambil user yang sedang login
    $user = Auth::user();

    // Tambahkan filter berdasarkan email (untuk cek NIP dosen)
    $query->when($user->email, function ($query) use ($user) {
        // Periksa apakah ada dosen dengan NIP sesuai email
        $hasDosen = Dosen::where('NIP', $user->email)->exists();

        if ($hasDosen) {
            // Jika email cocok dengan NIP dosen, filter berdasarkan NIP
            $query->whereHas('pemetaan.dosen', function ($q) use ($user) {
                $q->where('nip', $user->email);
            });
        }
        // Jika tidak cocok, jangan tambahkan filter, tampilkan semua jadwal
    });

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
    })
    // Tambahkan filter untuk memastikan bahwa tanggal mulai dan selesai jadwal sesuai dengan kondisi yang relevan
    ->whereHas('pemetaan', function ($query) {
        // Filter untuk memastikan tanggal mulai dan selesai sesuai dengan rentang yang valid
        $query->whereDate('tanggal_mulai', '<=', now())
              ->whereDate('tanggal_selesai', '>=', now());
    });

    // Ambil data jadwal yang sudah difilter
    $datas = $query->get();

    // Ambil daftar ruangan untuk form filter
    $ruangan = Ruangan::all();

    // Ambil daftar mata kuliah untuk form filter
    $matakuliah = MataKuliah::all();

    // Ambil seluruh data pemetaan dengan relasi mata_kuliah dan dosen
    $pemetaan = Pemetaan::with(['mata_kuliah', 'dosen'])->get();
    $dosen = Dosen::all();

    // Kembalikan view dengan data jadwal yang sudah difilter
    return view('jadwal_ruangan.index', compact('ruangan', 'datas', 'matakuliah', 'pemetaan','dosen'));
}



public function printJadwalPDF(Request $request)
{
    $mataKuliahId = $request->input('mata_kuliah');
    $dosenId = $request->input('dosen');
    $hari = $request->input('hari');
    $ruanganId = $request->input('ruangan');

    $query = JadwalRuangan::query();

    // Filter berdasarkan mata kuliah
    if (!empty($mataKuliahId)) {
        $query->whereHas('pemetaan.mata_kuliah', function ($q) use ($mataKuliahId) {
            $q->where('id', $mataKuliahId);
        });
    }
    if (!empty($ruanganId)) {
        $query->whereHas('ruangan', function ($q) use ($ruanganId) {
            $q->where('id', $ruanganId);
        });
    }
    // Filter berdasarkan dosen
    if (!empty($dosenId)) {
        $query->whereHas('pemetaan.dosen', function ($q) use ($dosenId) {
            $q->where('id', $dosenId);
        });
    }
    if (!empty($hari)) {
        $query->whereHas('pemetaan', function ($q) use ($hari) {
            $q->where('hari', $hari);
        });
    }

    // Ambil data sesuai filter
    $datas = $query->get();

    // Siapkan data untuk PDF
    $pdfData = [
        'datas' => $datas,
    ];

    // Generate PDF menggunakan view
    $pdf = Pdf::loadView('jadwal_ruangan.pdf', $pdfData);

    // Download PDF
    return $pdf->download('jadwal_ruangan.pdf');
}

// public function store(Request $request)
// {
//     // Validasi data yang diterima dari form modal
//     $validated = $request->validate([
//         'ruangan_id' => 'required|exists:ruangans,id',
//     ]);

//     // Ambil data pemetaan terakhir yang baru saja ditambahkan atau diperbarui
//     $pemetaan = Pemetaan::latest()->first();

//     // Buat jadwal ruangan untuk pemetaan
//     JadwalRuangan::create([
//         'pemetaan_id' => $pemetaan->id,
//         'ruangan_id' => $validated['ruangan_id'],
//     ]);

//     // Redirect setelah sukses
//     return redirect()->route('log_ruangan.index')->with('success', 'Jadwal Ruangan berhasil ditambahkan!');
// }

public function store(Request $request)
{
    // Validate the incoming request
    $validated = $request->validate([
        'pemetaan_id' => 'required|exists:pemetaan,id',
        'ruangan_id' => 'required|exists:ruangans,id',
    ]);

    // Store the jadwal (schedule) entry
    JadwalRuangan::create([
        'pemetaan_id' => $request->pemetaan_id,
        'ruangan_id' => $request->ruangan_id,
        // Other fields like hari, jam_mulai, etc. can be added here
    ]);

    return redirect()->route('log_ruangan.index')->with('success', 'Jadwal Ruangan berhasil disimpan!');
}




}
