<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{


    public function run()
    {
        $dosens = DB::table('dosens')->select('NIP', 'Nama')->get();

        foreach ($dosens as $dosen) {
            // Buat user berdasarkan NIP
            DB::table('users')->updateOrInsert(
                ['email' => $dosen->NIP], // Identifikasi unik berdasarkan username
                [
                    'name' => $dosen->Nama,
                    'email' => $dosen->NIP,
                    'password' => Hash::make('password123'), // Password default
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        DB::table('users')->updateOrInsert(
            ['email' => 'admin111@gmail.com'], // Identifikasi unik berdasarkan username
            [
                'name' => 'Administrator',
                'email' => 'admin111@gmail.com',
                'password' => Hash::make('admin123'), // Password default admin
                'role' => 'admin', // Role untuk admin
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Tambahkan akun operator
        DB::table('users')->updateOrInsert(
            ['email' => 'operator111@gmail.com'], // Identifikasi unik berdasarkan username
            [
                'name' => 'Operator',
                'email' => 'operator111@gmail.com',
                'password' => Hash::make('operator123'), // Password default operator
                'role' => 'operator', // Role untuk operator
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
