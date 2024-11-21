<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;


class DataRuanganController extends Controller
{
    public function index()
    {
        $datas = Ruangan::all();
        return view('data_ruangan.index', compact('datas'));
    }

    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'nama_ruangan' => 'required|string|max:100',
            'kapasitas' => 'required|integer|min:0',
            'fasilitas' => 'nullable|string|max:255',

        ]);

        Ruangan::create($validated);

        return redirect()->route('data_ruangan.index')->with('success', 'Data created successfully!');
    }

    public function update(Request $request, string $id)
    {

        $Ruangan = Ruangan::findOrFail($id);

        $request->validate([
            'nama_ruangan' => 'required|string|max:100'. $Ruangan->id,
            'kapasitas' => 'required|integer|min:0',
            'fasilitas' => 'nullable|string|max:255',


        ]);

        $Ruangan->update($request->all());
        return redirect(route('data_ruangan.index'))->with('success', 'Data Ruangan berhasil diperbarui.');
    }


    public function delete(Ruangan $datas)
    {

        return view('data_ruangan.hapus', compact('datas'));
    }
    public function destroy($id){
        $datas = Ruangan::findorfail($id);
        $datas->delete();
        return redirect('/data_ruangan')->with('success', 'Berhasil Dihapus');
    }
    public function show($id)
    {
        $Ruangan = Ruangan::findOrFail($id);
        return view('Ruangan.show', compact('Ruangan'));
    }
}

