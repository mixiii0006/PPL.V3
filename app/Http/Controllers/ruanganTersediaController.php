<?php

namespace App\Http\Controllers;

use App\Models\Pemetaan;
use App\Models\Dosen;
use App\Models\JadwalRuangan;
use App\Models\MataKuliah;
use App\Models\Ruangan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class ruanganTersediaController extends Controller
{
//     public function index(Request $request)
// {
//     // Ambil parameter hari atau gunakan hari ini sebagai default
//     $hari = $request->input('hari', Carbon::now()->isoFormat('dddd'));
//     $durasi = 50; // Durasi dalam menit
//     $ruangan = Ruangan::all();

//     $availableRooms = [];

//     // Mulai perulangan untuk setiap ruangan
//     foreach ($ruangan as $ruang) {
//         // Ambil data jadwal ruangan dan relasi pemetaannya untuk hari yang dipilih
//         $jadwalRuangan = JadwalRuangan::where('ruangan_id', $ruang->id)
//             ->with(['pemetaan' => function ($query) use ($hari) {
//                 $query->where('hari', $hari);
//             }])
//             ->get();

//         // Filter hanya yang memiliki relasi pemetaan (jadwal yang ada)
//         $jadwalPemetaan = $jadwalRuangan->filter(function ($jadwal) {
//             return $jadwal->pemetaan !== null; // Pastikan pemetaan ada
//         });

//         // Urutkan berdasarkan jam_mulai
//         $jadwalPemetaan = $jadwalPemetaan->sortBy(function ($jadwal) {
//             return $jadwal->pemetaan->jam_mulai;
//         });

//         // Cek slot waktu yang tersedia
//         $availableSlots = [];
//         $lastEndTime = "08:00:00"; // Awal hari (bisa disesuaikan dengan waktu operasional)

//         // Looping untuk setiap jadwal pemetaan untuk mencari slot kosong
//         foreach ($jadwalPemetaan as $jadwal) {
//             // Hitung gap waktu antara jadwal sebelumnya dan jadwal saat ini
//             $gap = (strtotime($jadwal->pemetaan->jam_mulai) - strtotime($lastEndTime)) / 60; // Konversi ke menit

//             // Jika gap lebih besar atau sama dengan durasi yang diinginkan, slot tersedia
//             if ($gap >= $durasi) {
//                 $availableSlots[] = [
//                     'start' => $lastEndTime,
//                     'end' => date('H:i', strtotime($jadwal->pemetaan->jam_mulai)),
//                 ];
//             }
//             dd($jadwalPemetaan, $availableSlots);


//             // Update waktu akhir untuk slot berikutnya
//             $lastEndTime = $jadwal->pemetaan->jam_selesai;
//         }

//         // Cek waktu setelah jadwal terakhir (misalnya sampai pukul 16:00:00)
//         $endOfDay = "16:00:00"; // Akhir hari, bisa disesuaikan
//         $gap = (strtotime($endOfDay) - strtotime($lastEndTime)) / 60; // Gap dalam menit

//         // Jika gap setelah jadwal terakhir lebih besar atau sama dengan durasi, maka ada slot yang tersedia
//         if ($gap >= $durasi) {
//             $availableSlots[] = [
//                 'start' => $lastEndTime,
//                 'end' => date('H:i', strtotime($endOfDay)),
//             ];
//         }

//         // Jika ada slot yang tersedia, tambahkan ke availableRooms
//         if (!empty($availableSlots)) {
//             $availableRooms[] = [
//                 'ruangan' => $ruang->nama_ruangan, // Nama ruangan
//                 'slots' => $availableSlots, // Slot waktu yang tersedia
//             ];
//         }
//     }

//     // Ambil data tambahan untuk view (misalnya, mata kuliah, dosen, pemetaan)
//     $matakuliah = MataKuliah::all();
//     $dosen = Dosen::all();
//     $pemetaan = Pemetaan::all();

//     // Kembalikan view dengan data yang diperlukan
//     return view('ruangan_tersedia', compact('availableRooms', 'hari', 'durasi', 'matakuliah', 'dosen', 'pemetaan', 'ruangan'));
// }


public function index(Request $request)
{
    $matakuliah = MataKuliah::all();
    $dosen = Dosen::all();
    // Tentukan hari yang valid (Senin hingga Jumat)
    $validDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

    // Ambil hari dari query string atau set ke default jika tidak ada
    $hari = $request->query('hari', $validDays[0]); // Default ke 'Senin' jika tidak ada

    // Pastikan hari yang dipilih adalah salah satu dari Senin hingga Jumat
    if (!in_array($hari, $validDays)) {
        $hari = $validDays[0]; // Default ke Senin jika hari tidak valid
    }

    $durasi = 50; // Durasi dalam menit
    $ruangan = Ruangan::all();
    $availableRooms = [];

    // Perulangan untuk setiap ruangan
    foreach ($ruangan as $ruang) {
        $jadwalRuangan = JadwalRuangan::where('ruangan_id', $ruang->id)
            ->with(['pemetaan' => function ($query) use ($hari) {
                $query->where('hari', $hari);
            }])
            ->get();

        // Filter hanya yang memiliki relasi pemetaan (jadwal yang ada)
        $jadwalPemetaan = $jadwalRuangan->filter(function ($jadwal) {
            return $jadwal->pemetaan !== null;
        });

        // Urutkan berdasarkan jam_mulai
        $jadwalPemetaan = $jadwalPemetaan->sortBy(function ($jadwal) {
            return $jadwal->pemetaan->jam_mulai;
        });

        // Cek slot waktu yang tersedia
        $availableSlots = [];
        $lastEndTime = "08:00"; // Awal hari

        foreach ($jadwalPemetaan as $jadwal) {
            // Menambah 10 menit pada waktu mulai dan mengurangi 10 menit pada waktu selesai
            $startTime = date('H:i', strtotime($jadwal->pemetaan->jam_mulai) -600); // +10 menit
            $endTime = date('H:i', strtotime($jadwal->pemetaan->jam_selesai) +600);// -10 menit

            // Gap antara waktu terakhir dan waktu mulai baru
            $gap = (strtotime($startTime) - strtotime($lastEndTime)) / 60; // Gap dalam menit
            if ($gap >= $durasi) {
                $availableSlots[] = [
                    'start' => $lastEndTime,
                    'end' => $startTime, // Waktu akhir slot ini
                ];
            }

            $lastEndTime = $endTime; // Update last end time
        }

        // Cek waktu setelah jadwal terakhir
        $endOfDay = "16:00"; // Akhir hari
        $gap = (strtotime($endOfDay) - strtotime($lastEndTime)) / 60;
        if ($gap >= $durasi) {
            $availableSlots[] = [
                'start' => $lastEndTime,
                'end' => date('H:i', strtotime($endOfDay)),
            ];
        }

        if (!empty($availableSlots)) {
            $availableRooms[] = [
                'ruangan' => $ruang->nama_ruangan,
                'id' => $ruang->id,
                'jenis_ruangan' => $ruang->jenis_ruangan,
                'slots' => $availableSlots,
            ];
        }
    }

    // Kembalikan view dengan data yang diperlukan
    return view('ruangan_tersedia', compact('availableRooms', 'hari', 'durasi', 'ruangan', 'dosen', 'matakuliah'));
}






    public function show($day)
    {
        // Ambil data ruangan yang memiliki jadwal pada hari tertentu
        $ruangan = Ruangan::with(['jadwalRuangans' => function ($query) use ($day) {
            $query->whereHas('pemetaan', function ($query) use ($day) {
                // Filter berdasarkan hari yang diterima dari parameter
                $query->where('hari', $day)
                      ->whereDate('tanggal_mulai', '<=', now())
                      ->whereDate('tanggal_selesai', '>=', now());
            });
        }])->get();

        // Ambil data pemetaan, mata kuliah, dan dosen untuk tampilan tambahan
        $pemetaan = Pemetaan::with(['mata_kuliah', 'dosen'])->get();
        $matakuliah = MataKuliah::all();
        $dosen = Dosen::all();

        // Tampilkan view dengan data jadwal yang sesuai
        return view('ruangan_tersedia', compact('dosen', 'ruangan', 'day', 'pemetaan', 'matakuliah'));
    }

    // public function store(Request $request)
    // {
    //     // Validasi data
    //     $validated = $request->validate([
    //         'dosen_id' => 'required|exists:dosens,id',
    //         'matakuliah_id' => 'required|exists:mata_kuliahs,id',
    //         'nama_modul' => 'required|string|max:255',
    //         'hari' => 'required|string|max:10',
    //         'jam_mulai' => 'required|date_format:H:i',
    //         'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
    //         'tanggal_mulai' => 'required|date',
    //         'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
    //         'jenis_ruangan' => 'required|in:RD,RK,Seminar',
    //         'ruangan_id' => 'required|exists:ruangans,id',
    //     ]);

    //     // Validasi jumlah_mahasiswa jika jenis ruangan adalah RD
    //     if ($validated['jenis_ruangan'] === 'RD') {
    //         $validated['jumlah_mahasiswa'] = $request->validate([
    //             'jumlah_mahasiswa' => 'required|integer|min:1',
    //         ])['jumlah_mahasiswa'];
    //     } else {
    //         // Untuk RK atau Seminar, set jumlah_mahasiswa ke null
    //         $validated['jumlah_mahasiswa'] = null;
    //     }

    //     try {
    //         // Membuat pemetaan
    //         $pemetaan = Pemetaan::create($validated);

    //         // Cek jika input skip_create_jadwal ada
    //         if ($request->has('skip_create_jadwal') && $request->input('skip_create_jadwal') == 1) {
    //             // Menggunakan ruangan_id langsung, tidak perlu mencari ruangan
    //             $ruangan_id = $request->input('ruangan_id');

    //             // Menghapus jadwal lama jika ada (kombinasi pemetaan_id dan ruangan_id yang sama)
    //             JadwalRuangan::where('pemetaan_id', $pemetaan->id)
    //                 ->where('ruangan_id', $ruangan_id)
    //                 ->delete();

    //             // Membuat jadwal baru untuk pemetaan yang baru dibuat dan ruangan yang dipilih
    //             $jadwal = new JadwalRuangan([
    //                 'pemetaan_id' => $pemetaan->id,
    //                 'ruangan_id' => $ruangan_id, // Langsung menggunakan ruangan_id
    //             ]);

    //             // Simpan jadwal ruangan
    //             $jadwal->save();
    //         }

    //     } catch (ValidationException $e) {
    //         // Menangkap exception dari model dan mengembalikan pesan error
    //         return redirect()->back()->withErrors($e->errors())->withInput();
    //     }

    //     // Redirect ke halaman log ruangan dengan pesan sukses
    //     return redirect()->route('ruangan_tersedia.index')->with('success', 'Jadwal berhasil ditambahkan!');
    // }


    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'dosen_id' => 'required|exists:dosens,id',
            'matakuliah_id' => 'required|exists:mata_kuliahs,id',
            'nama_modul' => 'required|string|max:255',
            'hari' => 'required|string|max:10',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jenis_ruangan' => 'required|in:RD,RK,Seminar',
        ]);

        // Validasi jumlah_mahasiswa jika jenis ruangan adalah RD
        if ($validated['jenis_ruangan'] === 'RD') {
            $validated['jumlah_mahasiswa'] = $request->validate([
                'jumlah_mahasiswa' => 'required|integer|min:1',
            ])['jumlah_mahasiswa'];
        } else {
            // Untuk RK atau Seminar, set jumlah_mahasiswa ke null
            $validated['jumlah_mahasiswa'] = null;
        }

        // Buat pemetaan baru
        $pemetaan = Pemetaan::create($validated);

        if ($request->has('skip_create_jadwal') && $request->input('skip_create_jadwal') == 1) {
            // Cari ruangan berdasarkan ID
            $ruangan = Ruangan::findOrFail($request->input('ruangan_id'));

            // Periksa apakah jadwal lama dengan pemetaan_id yang sama dan ruangan_id yang sama sudah ada
            $existingJadwal = JadwalRuangan::where('pemetaan_id', $pemetaan->id)
                ->where('ruangan_id', $ruangan->id)
                ->first();

            // Jika ada jadwal lama, hapus
            if ($existingJadwal) {
                $existingJadwal->delete();
            }

            // Membuat jadwal baru untuk pemetaan yang baru dibuat dan ruangan yang dipilih
            $jadwal = new JadwalRuangan([
                'pemetaan_id' => $pemetaan->id,
                'ruangan_id' => $ruangan->id,
            ]);

            // Simpan jadwal ruangan
            $jadwal->save();

            return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan!');
        }

        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan!');
    }


    // public function store(Request $request)
    // {
    //     // Validasi data
    //     $validated = $request->validate([
    //         'dosen_id' => 'required|exists:dosens,id',
    //         'matakuliah_id' => 'required|exists:mata_kuliahs,id',
    //         'nama_modul' => 'required|string|max:255',
    //         'hari' => 'required|string|max:10',
    //         'jam_mulai' => 'required|date_format:H:i',
    //         'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
    //         'tanggal_mulai' => 'required|date',
    //         'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
    //         'jenis_ruangan' => 'required|in:RD,RK,Seminar',
    //     ]);

    //     // Validasi jumlah_mahasiswa jika jenis ruangan adalah RD
    //     if ($validated['jenis_ruangan'] === 'RD') {
    //         $validated['jumlah_mahasiswa'] = $request->validate([
    //             'jumlah_mahasiswa' => 'required|integer|min:1',
    //         ])['jumlah_mahasiswa'];
    //     } else {
    //         // Untuk RK atau Seminar, set jumlah_mahasiswa ke null
    //         $validated['jumlah_mahasiswa'] = null;
    //     }

    //     try {
    //         // Membuat pemetaan
    //         $pemetaan = Pemetaan::create($validated);

    //         // Cek jika input skip_create_jadwal ada
    //         if ($request->has('skip_create_jadwal') && $request->input('skip_create_jadwal') == 1) {
    //             // Cari ruangan berdasarkan ID
    //             $ruangan = Ruangan::findOrFail($request->input('ruangan_id'));

    //             // Membuat jadwal baru untuk pemetaan yang baru dibuat dan ruangan yang dipilih
    //             $jadwal = new JadwalRuangan([
    //                 'pemetaan_id' => $pemetaan->id,
    //                 'ruangan_id' => $ruangan->id,
    //             ]);

    //             // Simpan jadwal ruangan
    //             $jadwal->save();
    //         }

    //     } catch (ValidationException $e) {
    //         // Menangkap exception dari model dan mengembalikan pesan error
    //         return redirect()->back()->withErrors($e->errors())->withInput();
    //     }

    //     // Redirect ke halaman log ruangan dengan pesan sukses
    //     return redirect()->route('ruangan_tersedia.index')->with('success', 'Jadwal berhasil ditambahkan!');
    //     // return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan!');
    //     // return redirect('/ruangan_tersedia')->with('success', 'Jadwal berhasil ditambahkan!');
    //     // return view('ruangan_tersedia')->with('success', 'Jadwal berhasil ditambahkan!');



    // }


    public function update(Request $request, $id)
    {
        // Validasi data input
        $validated = $request->validate([
            'dosen_id' => 'required|exists:dosens,id',
            'matakuliah_id' => 'required|exists:mata_kuliahs,id',
            'nama_modul' => 'required|string|max:255',
            'hari' => 'required|string|max:10',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jenis_ruangan' => 'required|in:RD,RK,Seminar',
        ]);

        // Validasi tambahan untuk jumlah_mahasiswa jika jenis ruangan adalah RD
        if ($validated['jenis_ruangan'] === 'RD') {
            $validated['jumlah_mahasiswa'] = $request->validate([
                'jumlah_mahasiswa' => 'required|integer|min:1',
            ])['jumlah_mahasiswa'];
        } else {
            // Set jumlah_mahasiswa ke null untuk jenis ruangan selain RD
            $validated['jumlah_mahasiswa'] = null;
        }

        try {
            // Cari pemetaan berdasarkan ID
            $pemetaan = Pemetaan::findOrFail($id);

            // Update pemetaan dengan data yang tervalidasi
            $pemetaan->update($validated);

            // Periksa apakah jadwal ruangan perlu dibuat
            if (!$request->has('skip_create_jadwal') || $request->input('skip_create_jadwal') != 1) {
                // Validasi ruangan_id
                $ruanganId = $request->input('ruangan_id');
                $request->validate([
                    'ruangan_id' => 'required|exists:ruangans,id',
                ]);

                // Hapus jadwal lama jika ada
                JadwalRuangan::where('pemetaan_id', $pemetaan->id)
                    ->where('ruangan_id', $ruanganId)
                    ->delete();

                // Buat jadwal ruangan baru
                JadwalRuangan::create([
                    'pemetaan_id' => $pemetaan->id,
                    'ruangan_id' => $ruanganId,
                ]);
            }

            // Redirect dengan pesan sukses
            return redirect()->route('log_ruangan.index')->with('success', 'Data updated successfully!');
        } catch (ValidationException $e) {
            // Tangkap exception validasi
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Tangkap exception lainnya
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
        }
    }




//     public function store(Request $request)
//     {
//         // Validasi data
//         $validated = $request->validate([

//             'dosen_id' => 'required|exists:dosens,id',
//             'matakuliah_id' => 'required|exists:mata_kuliahs,id',
//             'nama_modul' => 'required|string|max:255',
//             'hari' => 'required|string|max:10',
//             'jam_mulai' => 'required|date_format:H:i',
//             'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
//             'tanggal_mulai' => 'required|date',
//             'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
//             'jenis_ruangan' => 'required|in:RD,RK,Seminar',
//             // 'jumlah_mahasiswa' => 'nullable|integer|min:1'
//         ]);

//         if ($validated['jenis_ruangan'] === 'RD') {
//             $validated['jumlah_mahasiswa'] = $request->validate([
//                 'jumlah_mahasiswa' => 'required|integer|min:1',  // If RD, jumlah_mahasiswa must be provided
//             ])['jumlah_mahasiswa'];
//         } else {
//             // For RK or Seminar, set jumlah_mahasiswa to null
//             $validated['jumlah_mahasiswa'] = null;
//         }

//         try {
//             // Memanggil fungsi untuk membuat jadwal ruangan setelah validasi sukses
//             $pemetaan = Pemetaan::create($validated);
//             // $pemetaan->createJadwalRuangan();  // Menambahkan jadwal ruangan ke pemetaan

//         } catch (ValidationException $e) {
//             // Menangkap exception dari model dan mengembalikan pesan error ke halaman
//             return redirect()->back()->withErrors($e->errors())->withInput();
//         }

//         // Pemetaan::create($validated);

//         return redirect()->route('log_ruangan.index')->with('success', 'Data created successfully!');
//     }

//     public function update(Request $request, string $id)
// {
//     // Ambil data pemetaan berdasarkan ID
//     $datas = Pemetaan::findOrFail($id);


//     // Validasi data

//         $validated = $request->validate([
//             'dosen_id' => 'required|exists:dosens,id',
//             'matakuliah_id' => 'required|exists:mata_kuliahs,id',
//             'nama_modul' => 'required|string|max:255',
//             'hari' => 'required|string|max:10',
//             'jam_mulai' => 'required|date_format:H:i',
//             'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
//             'tanggal_mulai' => 'required|date',
//             'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
//             'jenis_ruangan' => 'required|in:RD,RK,Seminar', // Hanya validasi untuk jenis ruangan
//         ]);



//     // Validasi jumlah_mahasiswa jika jenis ruangan adalah RD
//     if ($validated['jenis_ruangan'] === 'RD') {
//         $validated['jumlah_mahasiswa'] = $request->validate([
//             'jumlah_mahasiswa' => 'required|integer|min:1',
//         ])['jumlah_mahasiswa'];
//     } else {
//         // Jika jenis ruangan bukan RD, set jumlah_mahasiswa ke null
//         $validated['jumlah_mahasiswa'] = null;
//     }

//     // Perbarui data di tabel pemetaan
//     $datas->update($validated);

//     // Redirect dengan pesan sukses
//     return redirect()->route('log_ruangan.index')->with('success', 'Data Pemetaan berhasil diperbarui.');
// }


    // public function store(Request $request)
    // {
    //     // Validasi data kecuali ruangan_id
    //     $validated = $request->validate([
    //         'dosen_id' => 'required|exists:dosens,id',
    //         'matakuliah_id' => 'required|exists:mata_kuliahs,id',
    //         'nama_modul' => 'required|string|max:255',
    //         'hari' => 'required|string|max:10',
    //         'jam_mulai' => 'required|date_format:H:i',
    //         'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
    //         'tanggal_mulai' => 'required|date',
    //         'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
    //         'jenis_ruangan' => 'required|in:RD,RK,Seminar',
    //     ]);

    //     // Validasi jumlah_mahasiswa jika jenis ruangan adalah RD
    //     if ($validated['jenis_ruangan'] === 'RD') {
    //         $validated['jumlah_mahasiswa'] = $request->validate([
    //             'jumlah_mahasiswa' => 'required|integer|min:1',
    //         ])['jumlah_mahasiswa'];
    //     } else {
    //         $validated['jumlah_mahasiswa'] = null;
    //     }

    //     // Simpan data pemetaan
    //     $pemetaan = Pemetaan::create($validated);

    //     // Cek apakah flag skip_create_jadwal diatur
    //     $skipCreateJadwal = $request->has('skip_create_jadwal') && $request->input('skip_create_jadwal') == 1;

    //     if ($skipCreateJadwal) {
    //         // Cari ruangan yang tersedia
    //         $availableRooms = Ruangan::whereDoesntHave('jadwalRuangan', function ($query) use ($pemetaan) {
    //             $query->where('hari', $pemetaan->hari)
    //                 ->where('jam_mulai', '<=', $pemetaan->jam_selesai)
    //                 ->where('jam_selesai', '>=', $pemetaan->jam_mulai)
    //                 ->where('tanggal_mulai', '<=', $pemetaan->tanggal_selesai)
    //                 ->where('tanggal_selesai', '>=', $pemetaan->tanggal_mulai);
    //         })->get();

    //         // Kirim data ruangan yang tersedia ke view
    //         return view('pilih_ruangan.index', compact('pemetaan', 'availableRooms'));
    //     }

    //     // Jika flag tidak aktif, buat jadwal ruangan secara otomatis
    //     $pemetaan->createJadwalRuangan();

    //     // Redirect ke halaman log ruangan
    //     return redirect()->route('log_ruangan.index')->with('success', 'Data Pemetaan dan Jadwal Ruangan berhasil ditambahkan!');
    // }

    // public function store(Request $request)
    // {
    //     // Validasi data kecuali ruangan_id
    //     $validated = $request->validate([
    //         'dosen_id' => 'required|exists:dosens,id',
    //         'matakuliah_id' => 'required|exists:mata_kuliahs,id',
    //         'nama_modul' => 'required|string|max:255',
    //         'hari' => 'required|string|max:10',
    //         'jam_mulai' => 'required|date_format:H:i',
    //         'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
    //         'tanggal_mulai' => 'required|date',
    //         'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
    //         'jenis_ruangan' => 'required|in:RD,RK,Seminar',
    //     ]);

    //     // Validasi jumlah_mahasiswa jika jenis ruangan adalah RD
    //     if ($validated['jenis_ruangan'] === 'RD') {
    //         $validated['jumlah_mahasiswa'] = $request->validate([
    //             'jumlah_mahasiswa' => 'required|integer|min:1',
    //         ])['jumlah_mahasiswa'];
    //     } else {
    //         $validated['jumlah_mahasiswa'] = null;
    //     }

    //     // Simpan data pemetaan
    //     $pemetaan = Pemetaan::create($validated);

    //     // Cek apakah flag skip_create_jadwal diatur
    //     $skipCreateJadwal = $request->has('skip_create_jadwal') && $request->input('skip_create_jadwal') == 1;

    //     if ($skipCreateJadwal) {
    //         // Cari ruangan yang tersedia
    //         $availableRooms = Ruangan::whereDoesntHave('jadwalRuangan', function ($query) use ($pemetaan) {
    //             $query->where('hari', $pemetaan->hari)
    //                 ->where('jam_mulai', '<=', $pemetaan->jam_selesai)
    //                 ->where('jam_selesai', '>=', $pemetaan->jam_mulai)
    //                 ->where('tanggal_mulai', '<=', $pemetaan->tanggal_selesai)
    //                 ->where('tanggal_selesai', '>=', $pemetaan->tanggal_mulai);
    //         })->get();

    //         // Kirim data ruangan yang tersedia ke session
    //         session()->flash('availableRooms', $availableRooms);
    //         session()->flash('pemetaanId', $pemetaan->id);

    //         // Redirect ke modal pemilihan ruangan
    //         return redirect()->route('pilih_ruangan')->with('success', 'Data Pemetaan berhasil ditambahkan. Pilih ruangan untuk melanjutkan.');
    //     }

    //     // Jika flag tidak aktif, buat jadwal ruangan secara otomatis
    //     $pemetaan->createJadwalRuangan();

    //     // Redirect ke halaman log ruangan
    //     return redirect()->route('log_ruangan.index')->with('success', 'Data Pemetaan dan Jadwal Ruangan berhasil ditambahkan!');
    // }


    // public function update(Request $request, string $id)
    // {
    //     // Ambil data pemetaan berdasarkan ID
    //     $pemetaan = Pemetaan::findOrFail($id);
    //     $ruangan = Ruangan::all();

    //     // Validasi data kecuali ruangan_id
    //     $validated = $request->validate([
    //         'dosen_id' => 'required|exists:dosens,id',
    //         'matakuliah_id' => 'required|exists:mata_kuliahs,id',
    //         'nama_modul' => 'required|string|max:255',
    //         'hari' => 'required|string|max:10',
    //         'jam_mulai' => 'required|date_format:H:i',
    //         'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
    //         'tanggal_mulai' => 'required|date',
    //         'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
    //         'jenis_ruangan' => 'required|in:RD,RK,Seminar',
    //     ]);

    //     // Validasi jumlah_mahasiswa jika jenis ruangan adalah RD
    //     if ($validated['jenis_ruangan'] === 'RD') {
    //         $validated['jumlah_mahasiswa'] = $request->validate([
    //             'jumlah_mahasiswa' => 'required|integer|min:1',
    //         ])['jumlah_mahasiswa'];
    //     } else {
    //         $validated['jumlah_mahasiswa'] = null;
    //     }

    //     dd($validated);
    //     // Perbarui data pemetaan
    //     $pemetaan->update($validated);

    //     // Cek apakah flag skip_create_jadwal diatur
    //     $skipCreateJadwal = $request->has('skip_create_jadwal') && $request->input('skip_create_jadwal') == 1;

    //     if ($skipCreateJadwal) {
    //         // Cari ruangan yang tersedia
    //         $availableRooms = Ruangan::whereDoesntHave('jadwalRuangan', function ($query) use ($pemetaan) {
    //             $query->where('hari', $pemetaan->hari)
    //                 ->where('jam_mulai', '<=', $pemetaan->jam_selesai)
    //                 ->where('jam_selesai', '>=', $pemetaan->jam_mulai)
    //                 ->where('tanggal_mulai', '<=', $pemetaan->tanggal_selesai)
    //                 ->where('tanggal_selesai', '>=', $pemetaan->tanggal_mulai);
    //         })->get();

    //         // Kirim data ruangan yang tersedia ke session
    //         session()->flash('availableRooms', $availableRooms);
    //         session()->flash('pemetaanId', $pemetaan->id);



    //         // Redirect ke modal pemilihan ruangan
    //         return redirect()->route('log_ruangan.pilih_ruangan')->with('success', 'Data Pemetaan berhasil diperbarui. Pilih ruangan untuk melanjutkan.');
    //     }

    //     // Jika flag tidak aktif, perbarui jadwal ruangan secara otomatis
    //     $pemetaan->updateJadwalRuangan();

    //     // Redirect ke halaman log ruangan
    //     return redirect()->route('log_ruangan.index')->with('success', 'Data Pemetaan dan Jadwal Ruangan berhasil diperbarui!');
    // }






    // public function store(Request $request)
    // {
    //     // Validasi data kecuali ruangan_id
    //     $validated = $request->validate([
    //         'dosen_id' => 'required|exists:dosens,id',
    //         'matakuliah_id' => 'required|exists:mata_kuliahs,id',
    //         'nama_modul' => 'required|string|max:255',
    //         'hari' => 'required|string|max:10',
    //         'jam_mulai' => 'required|date_format:H:i',
    //         'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
    //         'tanggal_mulai' => 'required|date',
    //         'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
    //         'ruangan_id' => 'required|exists:ruangans,id',
    //     ]);

    //     if ($request->has('skip_create_jadwal')) {
    //         session(['skip_create_jadwal' => true]);
    //     }

    //     // Membuat entri pemetaan
    //     $pemetaan = Pemetaan::create($validated);

    //     // Buat jadwal ruangan secara manual jika diperlukan
    //     if ($request->has('skip_create_jadwal')) {
    //         JadwalRuangan::create([
    //             'pemetaan_id' => $pemetaan->id,
    //             'ruangan_id' => $validated['ruangan_id'],
    //         ]);
    //     }

    //     // Kembalikan flag ke nilai default (false) setelah pemetaan dibuat
    //     session(['skip_create_jadwal' => false]);

    //     // Redirect ke halaman yang sesuai
    //     return redirect()->route('log_ruangan.index')->with('success', 'Data berhasil ditambahkan!');


    // }

    // public function update(Request $request, string $id)
    // {
    //     // Ambil data pemetaan berdasarkan ID
    //     $datas = Pemetaan::findOrFail($id);


    //     // Validasi data

    //         $validated = $request->validate([
    //             'dosen_id' => 'required|exists:dosens,id',
    //             'matakuliah_id' => 'required|exists:mata_kuliahs,id',
    //             'nama_modul' => 'required|string|max:255',
    //             'hari' => 'required|string|max:10',
    //             'jam_mulai' => 'required|date_format:H:i',
    //             'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
    //             'tanggal_mulai' => 'required|date',
    //             'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
    //             'jenis_ruangan' => 'required|in:RD,RK,Seminar', // Hanya validasi untuk jenis ruangan
    //         ]);



    //     // Validasi jumlah_mahasiswa jika jenis ruangan adalah RD
    //     if ($validated['jenis_ruangan'] === 'RD') {
    //         $validated['jumlah_mahasiswa'] = $request->validate([
    //             'jumlah_mahasiswa' => 'required|integer|min:1',
    //         ])['jumlah_mahasiswa'];
    //     } else {
    //         // Jika jenis ruangan bukan RD, set jumlah_mahasiswa ke null
    //         $validated['jumlah_mahasiswa'] = null;
    //     }

    //     // Perbarui data di tabel pemetaan
    //     $datas->update($validated);

    //     // Redirect dengan pesan sukses
    //     return redirect()->route('pemetaan_mk.index')->with('success', 'Data Pemetaan berhasil diperbarui.');
    // }

    public function delete(Pemetaan $datas)
    {
        return view('log_ruangan.hapus', compact('datas'));
    }
    public function destroy($id){
        $datas = Pemetaan::findorfail($id);
        $datas->delete();
        return redirect('/log_ruangan')->with('success', 'Data berhasil dihapus.');
    }



// public function importCSV(Request $request)
// {
//     $request->validate([
//         'csv_file' => 'required|file|mimes:csv,txt|max:2048',
//     ]);

//     $file = $request->file('csv_file');
//     $path = $file->getRealPath();

//     // Membaca semua baris CSV
//     $csvData = array_map(function ($line) {
//         return str_getcsv($line, ';'); // Pisahkan berdasarkan pemisah ;
//     }, file($path));

//     // Ambil header (nama kolom) dan hapus dari data
//     $header = array_shift($csvData);

//     $errorRows = [];
//     foreach ($csvData as $key => $row) {
//         // Gabungkan header dengan data agar lebih mudah diakses
//         $row = array_combine($header, $row);

//         // Cari ID berdasarkan nama dosen dan mata kuliah
//         $dosen = Dosen::where('Nama', $row['nama_dosen'])->first();
//         $matakuliah = MataKuliah::where('nama_matakuliah', $row['nama_modul'])->first();

//         if (!$dosen || !$matakuliah) {
//             $errorRows[$key + 1] = "Dosen atau Mata Kuliah tidak ditemukan: " . $row['nama_dosen'] . " / " . $row['nama_modul'];
//             continue;
//         }

//         // Mengonversi tanggal ke format yang benar (Y-m-d)
//         $tanggalMulai = Carbon::createFromFormat('d/m/Y', $row['tanggal_mulai'])->format('Y-m-d');
//         $tanggalSelesai = Carbon::createFromFormat('d/m/Y', $row['tanggal_selesai'])->format('Y-m-d');

//         // Validasi tanggal mulai dan selesai
//         if (Carbon::parse($tanggalMulai)->gt(Carbon::parse($tanggalSelesai))) {
//             $errorRows[$key + 1][] = "Tanggal mulai harus sebelum atau sama dengan tanggal selesai.";
//             continue;
//         }

//         // Validasi data lainnya
//         $validator = Validator::make($row, [
//             'judul_kuliah' => 'required|string|max:255',
//             'hari' => 'required|string|max:20',
//             'jam_mulai' => 'required|date_format:H:i',
//             'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
//             'jenis_ruangan' => 'required|string|in:RD,RK,Seminar',
//             'jumlah_mahasiswa' => 'nullable|integer|min:0',
//         ]);

//         if ($validator->fails()) {
//             dd($validator->errors()->all());
//         }

//         // Simpan data ke tabel `pemetaans`
//         $pemetaan = Pemetaan::create([
//             'dosen_id' => $dosen->id,
//             'matakuliah_id' => $matakuliah->id,
//             'nama_modul' => $row['judul_kuliah'],
//             'hari' => $row['hari'],
//             'jam_mulai' => $row['jam_mulai'],
//             'jam_selesai' => $row['jam_selesai'],
//             'tanggal_mulai' => $tanggalMulai, // Use the formatted date
//             'tanggal_selesai' => $tanggalSelesai, // Use the formatted date
//             'jenis_ruangan' => $row['jenis_ruangan'],
//             'jumlah_mahasiswa' => $row['jumlah_mahasiswa'],
//         ]);

//         // Pastikan jadwal langsung dibuat setelah pemetaan dibuat
//         // $pemetaan->createJadwalRuangan();

//     }

//     if (!empty($errorRows)) {
//         return back()->with('errors', $errorRows)->with('success', 'Sebagian data berhasil diimpor.');
//     }

//     return redirect('/pemetaan_mk')->with('success', 'Semua data berhasil diimpor.');

// }

}
