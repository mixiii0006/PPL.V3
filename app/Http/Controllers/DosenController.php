<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index()
    {
        $datas = Dosen::all();
        return view('data_dosen.index', compact('datas'));
    }

    public function create()
    {
        return view('data_dosen.create'); // Menampilkan form untuk tabel dosen
    }


    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([

            'NIP' => 'required|unique:dosens,NIP',
            'Nama' => 'required|string|max:100'

        ]);

        Dosen::create($validated);

        // Redirect ke halaman index
        return redirect()->route('data_dosen.index')->with('success', 'Data Dosen berhasil dibuat!');
    }

    public function edit($id)
    {
        $datas = Dosen::findOrFail($id);
        return view('data_dosen.index', compact('datas'));
    }

    public function update(Request $request, string $id)
    {

        $dosen = Dosen::findOrFail($id);

        $request->validate([
            'NIP' => 'required|unique:dosens,NIP,' . $dosen->id,
            'Nama' => 'required|string|max:100'
        ]);

        $dosen->update($request->all());
        return redirect(route('data_dosen.index'))->with('success', 'Data Dosen berhasil diperbarui.');
    }


    public function delete(Dosen $datas)
    {

        return view('data_dosen.hapus', compact('datas'));
    }
    public function destroy($id){
        $datas = Dosen::findorfail($id);
        $datas->delete();
        return redirect('/data_dosen')->with('success', 'Berhasil Dihapus');
    }
    public function show($id)
    {
        $dosen = Dosen::findOrFail($id);
        return view('dosen.show', compact('dosen'));
    }

}








