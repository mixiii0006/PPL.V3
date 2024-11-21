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
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required'
        ]);

        Pemetaan::create($validated);

        return redirect()->route('pemetaan_mk.index')->with('success', 'Data created successfully!');
    }

public function update(Request $request, string $id)
{

    $datas = Pemetaan::findOrFail($id);

    $request->validate([
            'dosen_id' => 'required|exists:dosens,id',
            'matakuliah_id' => 'required|exists:mata_kuliahs,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required'

    ]);

    $datas->update($request->all());
    return redirect(route('pemetaan_mk.index'))->with('success', 'Data Pemetaan berhasil diperbarui.');
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
