<?php

namespace App\Http\Controllers;

use App\Models\Pemetaan;
use App\Models\Dosen;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PemetaanMKController extends Controller
{
    public function index()
{
    $datas = Pemetaan::with(['Dosen', 'mata_kuliah'])->get();
    $matakuliah = MataKuliah::all();
    $dosen = Dosen::all();
    return view('pemetaan_mk.index', compact('datas', 'matakuliah', 'dosen'));
}

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
            // 'jumlah_mahasiswa' => 'nullable|integer|min:1'
        ]);

        if ($validated['jenis_ruangan'] === 'RD') {
            $validated['jumlah_mahasiswa'] = $request->validate([
                'jumlah_mahasiswa' => 'required|integer|min:1',  // If RD, jumlah_mahasiswa must be provided
            ])['jumlah_mahasiswa'];
        } else {
            // For RK or Seminar, set jumlah_mahasiswa to null
            $validated['jumlah_mahasiswa'] = null;
        }


        Pemetaan::create($validated);

        return redirect()->route('pemetaan_mk.index')->with('success', 'Data created successfully!');
    }

    public function update(Request $request, string $id)
{
    // Ambil data pemetaan berdasarkan ID
    $datas = Pemetaan::findOrFail($id);


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
        'jenis_ruangan' => 'required|in:RD,RK,Seminar', // Hanya validasi untuk jenis ruangan
    ]);


    // Validasi jumlah_mahasiswa jika jenis ruangan adalah RD
    if ($validated['jenis_ruangan'] === 'RD') {
        $validated['jumlah_mahasiswa'] = $request->validate([
            'jumlah_mahasiswa' => 'required|integer|min:1',
        ])['jumlah_mahasiswa'];
    } else {
        // Jika jenis ruangan bukan RD, set jumlah_mahasiswa ke null
        $validated['jumlah_mahasiswa'] = null;
    }

    // Perbarui data di tabel pemetaan
    $datas->update($validated);

    // Redirect dengan pesan sukses
    return redirect()->route('pemetaan_mk.index')->with('success', 'Data Pemetaan berhasil diperbarui.');
}





public function delete(Pemetaan $datas)
{

    return view('pemetaan_mk.hapus', compact('datas'));
}
public function destroy($id){
    $datas = Pemetaan::findorfail($id);
    $datas->delete();
    return redirect('/pemetaan_mk');
}

public function importCSV(Request $request)
{
    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt|max:2048',
    ]);

    $file = $request->file('csv_file');
    $path = $file->getRealPath();

    // Membaca semua baris CSV
    $csvData = array_map(function ($line) {
        return str_getcsv($line, ';'); // Pisahkan berdasarkan pemisah ;
    }, file($path));

    // Ambil header (nama kolom) dan hapus dari data
    $header = array_shift($csvData);

    $errorRows = [];
    foreach ($csvData as $key => $row) {
        // Gabungkan header dengan data agar lebih mudah diakses
        $row = array_combine($header, $row);

        // Cari ID berdasarkan nama dosen dan mata kuliah
        $dosen = Dosen::where('Nama', $row['nama_dosen'])->first();
        $matakuliah = MataKuliah::where('nama_matakuliah', $row['nama_modul'])->first();

        if (!$dosen || !$matakuliah) {
            $errorRows[$key + 1] = "Dosen atau Mata Kuliah tidak ditemukan: " . $row['nama_dosen'] . " / " . $row['nama_matakuliah'];
            continue;
        }

        // Mengonversi tanggal ke format yang benar (Y-m-d)
        $tanggalMulai = Carbon::createFromFormat('d/m/Y', $row['tanggal_mulai'])->format('Y-m-d');
        $tanggalSelesai = Carbon::createFromFormat('d/m/Y', $row['tanggal_selesai'])->format('Y-m-d');

        // Validasi tanggal mulai dan selesai
        if (Carbon::parse($tanggalMulai)->gt(Carbon::parse($tanggalSelesai))) {
            $errorRows[$key + 1][] = "Tanggal mulai harus sebelum atau sama dengan tanggal selesai.";
            continue;
        }

        // Validasi data lainnya
        $validator = Validator::make($row, [
            'judul_kuliah' => 'required|string|max:255',
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'jenis_ruangan' => 'required|string|in:RD,RK,Seminar',
            'jumlah_mahasiswa' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            dd($validator->errors()->all());
        }

        // Simpan data ke tabel `pemetaans`
        $pemetaan = Pemetaan::create([
            'dosen_id' => $dosen->id,
            'matakuliah_id' => $matakuliah->id,
            'nama_modul' => $row['judul_kuliah'],
            'hari' => $row['hari'],
            'jam_mulai' => $row['jam_mulai'],
            'jam_selesai' => $row['jam_selesai'],
            'tanggal_mulai' => $tanggalMulai, // Use the formatted date
            'tanggal_selesai' => $tanggalSelesai, // Use the formatted date
            'jenis_ruangan' => $row['jenis_ruangan'],
            'jumlah_mahasiswa' => $row['jumlah_mahasiswa'],
        ]);

        // Pastikan jadwal langsung dibuat setelah pemetaan dibuat
        $pemetaan->createJadwalRuangan();

    }

    if (!empty($errorRows)) {
        return back()->with('errors', $errorRows)->with('success', 'Sebagian data berhasil diimpor.');
    }

    return back()->with('success', 'Semua data berhasil diimpor.');
}




}
