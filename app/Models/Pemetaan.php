<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class Pemetaan extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'dosen_id',
        'matakuliah_id',
        'nama_modul',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'tanggal_mulai',
        'tanggal_selesai',
        'jenis_ruangan',
        'jumlah_mahasiswa'
    ];

    // Event untuk menyimpan jadwal ruangan secara otomatis
    protected static function booted()
    {
        static::created(function ($pemetaan) {
            // Setelah pemetaan dibuat, langsung buat jadwal ruangan
            $pemetaan->createJadwalRuangan();
        });

        static::updated(function ($pemetaan) {
            // Setelah pemetaan diperbarui, update jadwal ruangan
            $pemetaan->updateJadwalRuangan();
        });
    }

    // public function createJadwalRuangan()
    // {
    //     $jam_masuk = $this->jam_mulai;
    //     $jam_keluar = $this->jam_selesai;
    //     $hari = $this->hari;
    //     $jenis_ruangan = $this->jenis_ruangan;
    //     $jumlah_mahasiswa = $this->jumlah_mahasiswa;
    //     $tanggal_mulai = $this->tanggal_mulai;
    //     $tanggal_selesai = $this->tanggal_selesai;

    //     // Periksa apakah tanggal saat ini berada dalam rentang waktu yang sesuai
    //     $currentDate = now()->toDateString();
    //     if ($currentDate < $tanggal_mulai || $currentDate > $tanggal_selesai) {
    //         return;
    //     }

    //     // Validasi untuk jenis ruangan RD
    //     if ($jenis_ruangan === 'RD' && $jumlah_mahasiswa > 0) {
    //         $capacity = Ruangan::where('jenis_ruangan', 'RD')->first()->kapasitas;
    //         $neededRooms = ceil($jumlah_mahasiswa / $capacity);

    //         // Cari ruangan yang tersedia dan tidak bertabrakan dengan jadwal lain
    //         $availableRooms = Ruangan::where('jenis_ruangan', 'RD')
    //             ->whereNotIn('id', function ($query) use ($jam_masuk, $jam_keluar, $hari) {
    //                 $query->select('ruangan_id')
    //                     ->from('jadwal_ruangans')
    //                     ->join('pemetaans', 'jadwal_ruangans.pemetaan_id', '=', 'pemetaans.id')
    //                     ->where('pemetaans.hari', $hari)
    //                     ->where(function ($query) use ($jam_masuk, $jam_keluar) {
    //                         $query->whereBetween('pemetaans.jam_mulai', [$jam_masuk, $jam_keluar])
    //                             ->orWhereBetween('pemetaans.jam_selesai', [$jam_masuk, $jam_keluar])
    //                             ->orWhere(function ($query) use ($jam_masuk, $jam_keluar) {
    //                                 $query->where('pemetaans.jam_mulai', '<=', $jam_masuk)
    //                                     ->where('pemetaans.jam_selesai', '>=', $jam_keluar);
    //                             });
    //                     });
    //             })
    //             ->orderByRaw("CAST(REPLACE(nama_ruangan, 'RD', '') AS UNSIGNED) ASC") // Urutkan berdasarkan angka setelah RD
    //             ->take($neededRooms)
    //             ->get();

    //         if ($availableRooms->count() < $neededRooms) {
    //             return redirect()->back()->withErrors(['rooms' => 'Tidak cukup ruangan RD tersedia untuk jumlah mahasiswa.']);
    //         }

    //         // Hapus jadwal lama jika ada dan buat jadwal baru di tabel jadwal_ruangan
    //         JadwalRuangan::where('pemetaan_id', $this->id)->delete();

    //         foreach ($availableRooms as $room) {
    //             JadwalRuangan::create([
    //                 'pemetaan_id' => $this->id,
    //                 'ruangan_id' => $room->id,
    //             ]);
    //         }
    //     } elseif ($jenis_ruangan === 'RK' || $jenis_ruangan === 'Seminar') {
    //         // Untuk jenis ruangan RK atau Seminar, pastikan pengurutan sesuai
    //         $availableRoom = Ruangan::where('jenis_ruangan', $jenis_ruangan)
    //             ->whereNotIn('id', function ($query) use ($jam_masuk, $jam_keluar, $hari) {
    //                 $query->select('ruangan_id')
    //                     ->from('jadwal_ruangans')
    //                     ->join('pemetaans', 'jadwal_ruangans.pemetaan_id', '=', 'pemetaans.id')
    //                     ->where('pemetaans.hari', $hari)
    //                     ->where(function ($query) use ($jam_masuk, $jam_keluar) {
    //                         $query->whereBetween('pemetaans.jam_mulai', [$jam_masuk, $jam_keluar])
    //                             ->orWhereBetween('pemetaans.jam_selesai', [$jam_masuk, $jam_keluar])
    //                             ->orWhere(function ($query) use ($jam_masuk, $jam_keluar) {
    //                                 $query->where('pemetaans.jam_mulai', '<=', $jam_masuk)
    //                                     ->where('pemetaans.jam_selesai', '>=', $jam_keluar);
    //                             });
    //                     });
    //             })
    //             ->orderByRaw("CAST(REPLACE(nama_ruangan, 'RK', '') AS UNSIGNED) ASC") // Urutkan berdasarkan angka setelah RK
    //             ->first();

    //         if ($availableRoom) {
    //             JadwalRuangan::where('pemetaan_id', $this->id)->delete();
    //             JadwalRuangan::create([
    //                 'pemetaan_id' => $this->id,
    //                 'ruangan_id' => $availableRoom->id,
    //             ]);
    //         } else {
    //             return redirect()->back()->withErrors(['rooms' => 'Tidak ada ruangan RK atau Seminar yang tersedia pada jam dan hari ini.']);
    //         }
    //     }
    // }


    public function createJadwalRuangan()
    {
        Log::info('createJadwalRuangan called');
        $jam_masuk = $this->jam_mulai;
        $jam_keluar = $this->jam_selesai;
        $hari = $this->hari;
        $jenis_ruangan = $this->jenis_ruangan;
        $jumlah_mahasiswa = $this->jumlah_mahasiswa;
        $tanggal_mulai = $this->tanggal_mulai;
        $tanggal_selesai = $this->tanggal_selesai;

        // Hapus pengecekan tanggal mulai yang membatasi pembuatan jadwal
        // Tidak ada lagi pengecekan seperti ini:
        // $currentDate = now()->toDateString();
        // if ($currentDate < $tanggal_mulai) {
        //    return;
        // }

        // Pastikan jadwal yang dimasukkan berada dalam rentang tanggal yang valid
        if ($tanggal_selesai >= now()->toDateString()) {
            // Validasi untuk jenis ruangan RD
            if ($jenis_ruangan === 'RD' && $jumlah_mahasiswa > 0) {
                $capacity = Ruangan::where('jenis_ruangan', 'RD')->first()->kapasitas;
                $neededRooms = ceil($jumlah_mahasiswa / $capacity);

                // Cari ruangan yang tersedia dan tidak bertabrakan dengan jadwal lain pada tanggal mulai tersebut
                $availableRooms = Ruangan::where('jenis_ruangan', 'RD')
                    ->whereNotIn('id', function ($query) use ($jam_masuk, $jam_keluar, $hari, $tanggal_mulai, $tanggal_selesai) {
                        $query->select('ruangan_id')
                            ->from('jadwal_ruangans')
                            ->join('pemetaans', 'jadwal_ruangans.pemetaan_id', '=', 'pemetaans.id')
                            ->where('pemetaans.hari', $hari)
                            ->where(function ($query) use ($tanggal_mulai, $tanggal_selesai) {
                                // Periksa apakah tanggal mulai atau tanggal selesai bentrok dengan jadwal lain
                                $query->whereBetween('pemetaans.tanggal_mulai', [$tanggal_mulai, $tanggal_selesai])
                                    ->orWhereBetween('pemetaans.tanggal_selesai', [$tanggal_mulai, $tanggal_selesai])
                                    ->orWhere(function ($query) use ($tanggal_mulai, $tanggal_selesai) {
                                        $query->where('pemetaans.tanggal_mulai', '<=', $tanggal_mulai)
                                            ->where('pemetaans.tanggal_selesai', '>=', $tanggal_selesai);
                                    });
                            })
                            ->where(function ($query) use ($jam_masuk, $jam_keluar) {
                                $query->whereBetween('pemetaans.jam_mulai', [$jam_masuk, $jam_keluar])
                                    ->orWhereBetween('pemetaans.jam_selesai', [$jam_masuk, $jam_keluar])
                                    ->orWhere(function ($query) use ($jam_masuk, $jam_keluar) {
                                        $query->where('pemetaans.jam_mulai', '<', $jam_masuk)
                                            ->where('pemetaans.jam_selesai', '>', $jam_keluar);
                                    })
                                    ->orWhereRaw('? = pemetaans.jam_selesai AND ? = pemetaans.jam_mulai', [$jam_masuk, $jam_keluar]);
                            });
                    })
                    ->orderByRaw("CAST(REPLACE(TRIM(nama_ruangan), 'RD', '') AS UNSIGNED) ASC") // Urutkan berdasarkan angka setelah RD
                    ->take($neededRooms)
                    ->get();
                    Log::info('Needed rooms: ' . $neededRooms);
                    Log::info('Available rooms: ' . $availableRooms->count());

                if ($availableRooms->count() < $neededRooms) {
                     // Lemparkan pengecualian jika tidak cukup ruangan
                        throw ValidationException::withMessages([
                            'rooms' => 'Tidak cukup ruangan RD tersedia untuk jumlah mahasiswa.'
                        ]);
                }

                // Hapus jadwal lama jika ada dan buat jadwal baru di tabel jadwal_ruangan
                JadwalRuangan::where('pemetaan_id', $this->id)->delete();



                foreach ($availableRooms as $room) {

                    JadwalRuangan::create([
                        'pemetaan_id' => $this->id,
                        'ruangan_id' => $room->id,
                    ]);
                }

            } elseif ($jenis_ruangan === 'RK' || $jenis_ruangan === 'Seminar') {
                // Untuk jenis ruangan RK atau Seminar, pastikan pengurutan sesuai
                $availableRoom = Ruangan::where('jenis_ruangan', $jenis_ruangan)
                    ->whereNotIn('id', function ($query) use ($jam_masuk, $jam_keluar, $hari, $tanggal_mulai, $tanggal_selesai) {
                        $query->select('ruangan_id')
                            ->from('jadwal_ruangans')
                            ->join('pemetaans', 'jadwal_ruangans.pemetaan_id', '=', 'pemetaans.id')
                            ->where('pemetaans.hari', $hari)
                            ->where(function ($query) use ($tanggal_mulai, $tanggal_selesai) {
                                // Periksa apakah tanggal mulai atau tanggal selesai bentrok dengan jadwal lain
                                $query->whereBetween('pemetaans.tanggal_mulai', [$tanggal_mulai, $tanggal_selesai])
                                    ->orWhereBetween('pemetaans.tanggal_selesai', [$tanggal_mulai, $tanggal_selesai])
                                    ->orWhere(function ($query) use ($tanggal_mulai, $tanggal_selesai) {
                                        $query->where('pemetaans.tanggal_mulai', '<=', $tanggal_mulai)
                                            ->where('pemetaans.tanggal_selesai', '>=', $tanggal_selesai);
                                    });
                            })
                            ->where(function ($query) use ($jam_masuk, $jam_keluar) {
                                // Memeriksa jika jadwal lama bertabrakan dengan jadwal baru
                                    $query->where(function ($query) use ($jam_masuk, $jam_keluar) {
                                        $query->whereBetween('pemetaans.jam_mulai', [$jam_masuk, $jam_keluar]) // Memeriksa jika jam_mulai jadwal lama berada di antara jam_masuk dan jam_keluar
                                            ->orWhereBetween('pemetaans.jam_selesai', [$jam_masuk, $jam_keluar]) // Memeriksa jika jam_selesai jadwal lama berada di antara jam_masuk dan jam_keluar
                                            ->orWhere(function ($query) use ($jam_masuk, $jam_keluar) {
                                                $query->where('pemetaans.jam_mulai', '<=', $jam_masuk) // Memeriksa jika jam_mulai jadwal lama lebih awal atau sama dengan jam_masuk
                                                    ->where('pemetaans.jam_selesai', '>=', $jam_keluar); // dan jam_selesai lebih lambat atau sama dengan jam_keluar
                                            })
                                            ->orWhereRaw("pemetaans.jam_selesai = '$jam_masuk' OR pemetaans.jam_mulai = '$jam_keluar'"); // Memeriksa jika jadwal lama selesai tepat pada jam_masuk dan mulai tepat pada jam_keluar
                                    });
                            });


                    })
                    // Urutkan berdasarkan angka setelah RK
                    ->first();
                    Log::info('Available Room Query:', ['query' => $availableRoom ? $availableRoom->toSql() : 'No Room Found']);


                if ($availableRoom) {
                    JadwalRuangan::where('pemetaan_id', $this->id)->delete();
                    JadwalRuangan::create([
                        'pemetaan_id' => $this->id,
                        'ruangan_id' => $availableRoom->id,
                    ]);

                } else {
                    throw ValidationException::withMessages([
                        'rooms' => 'Tidak ada ruangan RK atau Seminar yang tersedia pada jam dan hari ini.'
                    ]);
                }
            }
        }
    }





    public function updateJadwalRuangan()
    {
        // Memanggil fungsi yang sama seperti create, namun hanya memperbarui jadwal yang ada
        $this->createJadwalRuangan();
    }


    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    // Define the relationship with MataKuliah
    public function mata_kuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'matakuliah_id');
    }
}



