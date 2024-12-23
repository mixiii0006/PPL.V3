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

class tambahRuangController extends Controller
{

    public function index()
    {
        // Set default hari ke Senin
        $defaultDay = 'Senin';

        // Ambil semua ruangan beserta jadwalnya yang di-filter berdasarkan hari
        $ruangan = Ruangan::with(['jadwalRuangans' => function ($query) use ($defaultDay) {
            $query->whereHas('pemetaan', function ($query) use ($defaultDay) {
                // Filter jadwal berdasarkan hari Senin
                $query->where('hari', $defaultDay);
            });
        }])->get();

        // Ambil data pemetaan dan lainnya untuk keperluan tambahan
        $datas = Pemetaan::with(['Dosen', 'mata_kuliah'])->get();
        $matakuliah = MataKuliah::all();
        $pemetaan = Pemetaan::all();
        $dosen = Dosen::all();

        // Kirim data ke view dengan hari default
        return view('tambah_ruangan', compact('ruangan', 'matakuliah', 'dosen', 'pemetaan', 'datas', 'defaultDay'));
    }


    public function show($day = 'Senin')  // Menambahkan default nilai hari
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
        return view('tambah_ruangan', compact('dosen', 'ruangan', 'day', 'pemetaan', 'matakuliah'));
    }

    // public function create(Request $request)
    // {
    //     $hari = $request->input('hari');
    //     $jamMulai = $request->input('jam_mulai');
    //     $jamSelesai = $request->input('jam_selesai');

    //     // Ambil semua ruangan
    //     $ruanganTersedia = Ruangan::whereDoesntHave('jadwalRuangan', function ($query) use ($hari, $jamMulai, $jamSelesai) {
    //         $query->where('hari', $hari)
    //             ->where(function ($subQuery) use ($jamMulai, $jamSelesai) {
    //                 $subQuery->whereBetween('jam_mulai', [$jamMulai, $jamSelesai])
    //                         ->orWhereBetween('jam_selesai', [$jamMulai, $jamSelesai])
    //                         ->orWhere(function ($nestedQuery) use ($jamMulai, $jamSelesai) {
    //                             $nestedQuery->where('jam_mulai', '<=', $jamMulai)
    //                                         ->where('jam_selesai', '>=', $jamSelesai);
    //                         });
    //             });
    //     })->get();

    //     return view('tambah_ruangan', compact('ruanganTersedia'));
    // }


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
    //     return redirect()->route('tambah_ruangan.index')->with('success', 'Jadwal berhasil ditambahkan!');
    // }

    // public function store(Request $request)
    // {

    //     // dd($request->all());
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


    //     $pemetaan = Pemetaan::create($validated);

    //     if ($request->has('skip_create_jadwal') && $request->input('skip_create_jadwal') == 1) {
    //         // Cari ruangan berdasarkan ID
    //         $ruangan = Ruangan::findOrFail($request->input('ruangan_id'));

    //         // Membuat jadwal baru untuk pemetaan yang baru dibuat dan ruangan yang dipilih
    //         $jadwal = new JadwalRuangan([
    //             'pemetaan_id' => $pemetaan->id,
    //             'ruangan_id' => $ruangan->id,
    //         ]);


    //         // Simpan jadwal ruangan
    //         $jadwal->save();
    //         return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan!');

    //     }

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




    //     return redirect()->back()->with('success', 'Jadwal gagal ditambahkan karena skip create bukan 1');



    // }


    // public function update(Request $request, $id)
    // {
    //     // Validasi data input
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
    //         // 'ruangan_id' => 'required|exists:ruangans,id',
    //     ]);

    //     // Validasi tambahan untuk jumlah_mahasiswa jika jenis ruangan adalah RD
    //     if ($validated['jenis_ruangan'] === 'RD') {
    //         $validated['jumlah_mahasiswa'] = $request->validate([
    //             'jumlah_mahasiswa' => 'required|integer|min:1',
    //         ])['jumlah_mahasiswa'];
    //     } else {
    //         // Set jumlah_mahasiswa ke null untuk jenis ruangan selain RD
    //         $validated['jumlah_mahasiswa'] = null;
    //     }

    //     try {
    //         // Cari pemetaan berdasarkan ID
    //         $pemetaan = Pemetaan::findOrFail($id);

    //         // Update pemetaan dengan data yang tervalidasi
    //         $pemetaan->update($validated);

    //         // Periksa apakah jadwal ruangan perlu dibuat
    //         if (!$request->has('skip_create_jadwal') || $request->input('skip_create_jadwal') != 1) {
    //             // Validasi ruangan_id
    //             $ruanganId = $request->input('ruangan_id');
    //             $request->validate([
    //                 'ruangan_id' => 'required|exists:ruangans,id',
    //             ]);

    //             // Hapus jadwal lama jika ada
    //             JadwalRuangan::where('pemetaan_id', $pemetaan->id)
    //                 ->where('ruangan_id', $ruanganId)
    //                 ->delete();

    //             // Buat jadwal ruangan baru
    //             JadwalRuangan::create([
    //                 'pemetaan_id' => $pemetaan->id,
    //                 'ruangan_id' => $ruanganId,
    //             ]);
    //         }

    //         // Redirect dengan pesan sukses
    //         return redirect()->route('tambah_ruangan.index')->with('success', 'Data updated successfully!');
    //     } catch (ValidationException $e) {
    //         // Tangkap exception validasi
    //         return redirect()->back()->withErrors($e->errors())->withInput();
    //     } catch (\Exception $e) {
    //         // Tangkap exception lainnya
    //         return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
    //     }
    // }

    //     public function store(Request $request)
    // {
    //     // Validasi input dari user
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

    //     // Membuat entri baru di tabel Pemetaan
    //     $pemetaan = Pemetaan::create([
    //         'dosen_id' => $validated['dosen_id'],
    //         'matakuliah_id' => $validated['matakuliah_id'],
    //         'nama_modul' => $validated['nama_modul'],
    //         'hari' => $validated['hari'],
    //         'jam_mulai' => $validated['jam_mulai'],
    //         'jam_selesai' => $validated['jam_selesai'],
    //         'tanggal_mulai' => $validated['tanggal_mulai'],
    //         'tanggal_selesai' => $validated['tanggal_selesai'],
    //         'jenis_ruangan' => $validated['jenis_ruangan'],
    //     ]);

    //     // Mencari ruangan yang tersedia berdasarkan jadwal
    //     $ruanganTersedia = Ruangan::whereDoesntHave('jadwalRuangan', function($query) use ($validated) {
    //         $query->where('hari', $validated['hari'])
    //               ->where(function ($q) use ($validated) {
    //                   $q->where('jam_mulai', '<', $validated['jam_selesai'])
    //                     ->where('jam_selesai', '>', $validated['jam_mulai']);
    //               });
    //     })->get();

    //     // Mendapatkan ruangan yang dipilih
    //     $ruanganSelected = Ruangan::find($validated['ruangan_id']);

    //     // Periksa apakah ruangan yang dipilih tersedia
    //     if (!$ruanganTersedia->contains('id', $ruanganSelected->id)) {
    //         return redirect()->back()->with('error', 'Ruangan tidak tersedia pada waktu yang dipilih.');
    //     }

    //     // Memeriksa apakah kita perlu membuat JadwalRuangan
    //     if (!$request->has('skip_create_jadwal') || $request->input('skip_create_jadwal') != 1) {
    //         // Membuat JadwalRuangan jika flag tidak diset atau tidak bernilai 1
    //         JadwalRuangan::create([
    //             'pemetaan_id' => $pemetaan->id,
    //             'ruangan_id' => $validated['ruangan_id'],
    //         ]);

    //         // Kembali ke halaman dengan pesan sukses
    //         return redirect()->route('tambah_ruangan')->with('success', 'Pemetaan dan Jadwal berhasil dibuat!');
    //     }

    //     // Jika skip_create_jadwal diset ke 1, lewati pembuatan JadwalRuangan
    //     return redirect()->route('tambah_ruangan')->with('success', 'Pemetaan berhasil dibuat, jadwal dilewati!');
    // }



    // public function store(Request $request)
    // {
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

    //     // Create the Pemetaan record
    //     $pemetaan = Pemetaan::create([
    //         'dosen_id' => $validated['dosen_id'],
    //         'matakuliah_id' => $validated['matakuliah_id'],
    //         'nama_modul' => $validated['nama_modul'],
    //         'hari' => $validated['hari'],
    //         'jam_mulai' => $validated['jam_mulai'],
    //         'jam_selesai' => $validated['jam_selesai'],
    //         'tanggal_mulai' => $validated['tanggal_mulai'],
    //         'tanggal_selesai' => $validated['tanggal_selesai'],
    //         'jenis_ruangan' => $validated['jenis_ruangan'],
    //     ]);

    //     // Check if we should create a JadwalRuangan
    //     if (!$request->has('skip_create_jadwal') || $request->input('skip_create_jadwal') != 1) {
    //         // Create the JadwalRuangan if the flag is not set or not equal to 1
    //         JadwalRuangan::create([
    //             'pemetaan_id' => $pemetaan->id,
    //             'ruangan_id' => $validated['ruangan_id'],
    //         ]);

    //         // Return with success message
    //         return redirect()->route('tambah_ruangan', 'Pemetaan dan Jadwal berhasil dibuat!');
    //     }

    //     // If skip_create_jadwal is set to 1, skip JadwalRuangan creation
    //     return redirect()->route('tambah_ruangan', 'Pemetaan berhasil dibuat, jadwal dilewati!');
    // }



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
    //     return redirect()->route('ruangan_tersedia', 'Data created successfully!');
    // }
    // public function update(Request $request, $id)
    // {
    //     // Validasi data input
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

    //     // Validasi tambahan untuk jumlah_mahasiswa jika jenis ruangan adalah RD
    //     if ($validated['jenis_ruangan'] === 'RD') {
    //         $validated['jumlah_mahasiswa'] = $request->validate([
    //             'jumlah_mahasiswa' => 'required|integer|min:1',
    //         ])['jumlah_mahasiswa'];
    //     } else {
    //         // Set jumlah_mahasiswa ke null untuk jenis ruangan selain RD
    //         $validated['jumlah_mahasiswa'] = null;
    //     }

    //     try {
    //         // Cari pemetaan berdasarkan ID
    //         $pemetaan = Pemetaan::findOrFail($id);

    //         // Update pemetaan dengan data yang tervalidasi
    //         $pemetaan->update($validated);

    //         // Periksa apakah jadwal ruangan perlu dibuat
    //         if (!$request->has('skip_create_jadwal') || $request->input('skip_create_jadwal') != 1) {
    //             // Validasi ruangan_id
    //             $ruanganId = $request->input('ruangan_id');
    //             $request->validate([
    //                 'ruangan_id' => 'required|exists:ruangans,id',
    //             ]);

    //             // Hapus jadwal lama jika ada
    //             JadwalRuangan::where('pemetaan_id', $pemetaan->id)
    //                 ->where('ruangan_id', $ruanganId)
    //                 ->delete();

    //             // Buat jadwal ruangan baru
    //             JadwalRuangan::create([
    //                 'pemetaan_id' => $pemetaan->id,
    //                 'ruangan_id' => $ruanganId,
    //             ]);
    //         }

    //         // Redirect dengan pesan sukses
    //         return redirect()->route('log_ruangan.index')->with('success', 'Data updated successfully!');
    //     } catch (ValidationException $e) {
    //         // Tangkap exception validasi
    //         return redirect()->back()->withErrors($e->errors())->withInput();
    //     } catch (\Exception $e) {
    //         // Tangkap exception lainnya
    //         return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
    //     }
    // }




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
        return view('tambah_ruangan.hapus', compact('datas'));
    }
    public function destroy($id)
    {
        $datas = Pemetaan::findorfail($id);
        $datas->delete();
        return redirect('/tambah_ruangan')->with('success', 'Data berhasil dihapus.');
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
