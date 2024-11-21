<?php

namespace App\Http\Controllers;

use App\Models\Pemetaan;
use App\Models\Dosen;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

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
            'jenis_ruangan' => 'required|in:RD,RK,Seminar',
        ]);

        // Jika jenis ruangan adalah RD, validasi jumlah_mahasiswa
        if ($validated['jenis_ruangan'] === 'RD') {
            $validated['jumlah_mahasiswa'] = $request->validate([
                'jumlah_mahasiswa' => 'required|integer|min:1', // Jika RD, jumlah_mahasiswa harus ada
            ])['jumlah_mahasiswa'];
        } else {
            // Jika jenis ruangan adalah RK atau Seminar, set jumlah_mahasiswa ke null
            $validated['jumlah_mahasiswa'] = null;
        }

        // Perbarui data di tabel pemetaan
        $datas->update($validated);

        return redirect()->route('pemetaan_mk.index')->with('success', 'Data Pemetaan berhasil diperbarui.');
    }



public function delete(Pemetaan $datas)
{

    return view('pemetaan_mk.hapus', compact('datas'));
}
public function destroy($id){
    $datas = Pemetaan::findorfail($id);
    $datas->delete();
    return redirect('/pemetaan_mk')->with('success', 'Berhasil Dihapus');
}

}
