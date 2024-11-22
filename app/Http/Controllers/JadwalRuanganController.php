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
        // Ambil data JadwalRuangan yang berada dalam rentang waktu yang valid (tanggal_mulai dan tanggal_selesai)
        $datas = JadwalRuangan::with(['ruangan', 'pemetaan.mata_kuliah', 'pemetaan.dosen'])
            ->whereHas('pemetaan', function ($query) {
                $query->whereDate('tanggal_mulai', '<=', now()) // Tanggal mulai dari tabel pemetaan
                    ->whereDate('tanggal_selesai', '>=', now()); // Tanggal selesai dari tabel pemetaan
            })
            ->get();

        // Ambil seluruh data pemetaan dengan relasi mata_kuliah dan dosen
        $pemetaan = Pemetaan::with(['mata_kuliah', 'dosen'])->get();

        // Ambil seluruh data ruangan
        $ruangan = Ruangan::all();



        // Kembalikan view dengan data yang dibutuhkan
        return view('jadwal_ruangan.index', compact('datas', 'pemetaan', 'ruangan',));


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


    public function update(Request $request, string $id)
    {

        $datas = JadwalRuangan::findOrFail($id);

        $request->validate([
                'pemetaan_id' => 'required|exists:pemetaans,id',
                'ruangan_id' => 'required|exists:ruangans,id',
        ]);

        $datas->update($request->all());
        return redirect(route('jadwal_ruangan.index'))->with('success', 'Data JadwalRuangan berhasil diperbarui.');
    }


    public function delete(JadwalRuangan $datas)
    {

        return view('jadwal_ruangan.hapus', compact('datas'));
    }
    public function destroy($id){
        $datas = JadwalRuangan::findorfail($id);
        $datas->delete();
        return redirect('/jadwal_ruangan')->with('success', 'Berhasil Dihapus');
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

        // Tampilkan view dengan data jadwal yang sesuai
        return view('jadwal_ruangan.index', compact('datas', 'day','pemetaan', 'ruangan'));
    }
    public function filterJadwal(Request $request)
    {
        // Ambil ID ruangan yang dipilih dari request
        $selectedRuanganIds = $request->input('ruangan_ids', []);

        // Ambil hari yang dipilih dari request
        $selectedDay = $request->input('hari', null);

        // Mulai query untuk mengambil data jadwal
        $query = JadwalRuangan::query();

        // Filter berdasarkan ID ruangan yang dipilih jika ada
        if (!empty($selectedRuanganIds)) {
            $query->whereIn('ruangan_id', $selectedRuanganIds);
        }

        // Filter berdasarkan hari yang dipilih jika ada
        if ($selectedDay) {
            $query->where('hari', $selectedDay);
        }

        // Ambil data jadwal yang sudah difilter
        $datas = $query->get();

        // Ambil daftar ruangan untuk form filter
        $ruangan = Ruangan::all();

        // Ambil daftar hari yang tersedia (untuk filter hari)
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        // Ambil data pemetaan untuk mata kuliah dan dosen
        $pemetaan = Pemetaan::with(['mata_kuliah', 'dosen'])->get();

        // Kembalikan view dengan data jadwal yang sudah difilter
        return view('jadwal_ruangan.index', compact('ruangan', 'datas', 'pemetaan', 'days'));
    }
}
