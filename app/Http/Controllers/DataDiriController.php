<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class DataDiriController extends Controller
{
    public function index()
    {
        $datas = User::all();
        return view('data_diri.index',
         compact('datas'));
    }

    public function create()
    {
        return view('data_diri.create');
    }

    // Fungsi untuk menyimpan data user baru
    public function store(Request $request)
    {


        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // Pastikan memeriksa kolom yang benar
            'password' => 'required|string|min:8|confirmed', // Validasi password
            'role' => 'required|in:admin,user', // Validasi role
        ]);
        dd($validated); // Debug data request yang dikirim

        // Simpan data user ke database
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // Menggunakan Hash untuk mengenkripsi password
            'role' => $validated['role'],
        ]);


        // Redirect setelah berhasil disimpan dengan pesan sukses
        return redirect(route('data_diri.index'))->with('success', 'User created successfully!');
    }



    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'email'=>'required|string|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,user,operator',

        ]);
        $datas = User::find($id);
        $datas->update($request->all());
        return redirect(route('data_diri.index'))->with('success', 'Berhasil Dihapus');
    }

    public function delete(User $datas)
    {

        return view('data_diri.hapus', compact('datas'));
    }
    public function destroy($id){
        $datas = User::findorfail($id);
        $datas->delete();
        return redirect('/data_diri')->with('success', 'Berhasil Dihapus');
    }
}

