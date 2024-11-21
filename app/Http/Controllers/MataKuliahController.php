<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index()
    {
        $datas = MataKuliah::all();
        return view('data_mk.index', compact('datas'));
    }

    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([

            'nama_matakuliah' => 'required|string|max:100',
            'tingkat' => 'required|integer|min:1',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai'

        ]);

        MataKuliah::create($validated);

        return redirect()->route('data_mk.index')->with('success', 'Data created successfully!');
    }

    public function update(Request $request, string $id)
    {

        $MataKuliah = MataKuliah::findOrFail($id);

        $request->validate([
            'nama_matakuliah' => 'required|string|max:100' . $MataKuliah->id,
            'tingkat' => 'required|integer|min:1',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai'

        ]);

        $MataKuliah->update($request->all());
        return redirect(route('data_mk.index'))->with('success', 'Data MataKuliah berhasil diperbarui.');
    }


    public function delete(MataKuliah $datas)
    {

        return view('data_mk.hapus', compact('datas'));
    }
    public function destroy($id){
        $datas = MataKuliah::findorfail($id);
        $datas->delete();
        return redirect('/data_mk')->with('success', 'Berhasil Dihapus');
    }
    public function show($id)
    {
        $MataKuliah = MataKuliah::findOrFail($id);
        return view('MataKuliah.show', compact('MataKuliah'));
    }
}

