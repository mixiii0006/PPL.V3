<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

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
            $pemetaan->createJadwalRuangan();
        });
    }

    public function createJadwalRuangan()
    {
        // Mengambil data dari pemetaan
        $jam_masuk = $this->jam_mulai;
        $jam_keluar = $this->jam_selesai;
        $hari = $this->hari;
        $jenis_ruangan = $this->jenis_ruangan;
        $jumlah_mahasiswa = $this->jumlah_mahasiswa;
        $tanggal_mulai = $this->tanggal_mulai;
        $tanggal_selesai = $this->tanggal_selesai;

        // Periksa apakah tanggal saat ini berada dalam rentang waktu yang sesuai
        $currentDate = now()->toDateString();  // Ambil tanggal saat ini
        if ($currentDate < $tanggal_mulai || $currentDate > $tanggal_selesai) {
            return;  // Jika tanggal saat ini tidak dalam rentang waktu, tidak buat jadwal
        }

        // Validasi untuk jenis ruangan RD
        if ($jenis_ruangan === 'RD' && $jumlah_mahasiswa > 0) {
            $ruangan = Ruangan::where('jenis_ruangan', 'RD')->first();

            if (!$ruangan) {
                return; // Tidak ada ruangan RD yang tersedia
            }

            $capacity = $ruangan->kapasitas;
            $neededRooms = ceil($jumlah_mahasiswa / $capacity);

            $availableRooms = Ruangan::where('jenis_ruangan', 'RD')
                ->whereNotIn('id', function ($query) use ($jam_masuk, $jam_keluar, $hari) {
                    $query->select('ruangan_id')
                        ->from('jadwal_ruangans')
                        ->where('hari', $hari)
                        ->where(function ($query) use ($jam_masuk, $jam_keluar) {
                            $query->whereBetween('jam_masuk', [$jam_masuk, $jam_keluar])
                                ->orWhereBetween('jam_keluar', [$jam_masuk, $jam_keluar])
                                ->orWhere(function ($query) use ($jam_masuk, $jam_keluar) {
                                    $query->where('jam_masuk', '<=', $jam_masuk)
                                        ->where('jam_keluar', '>=', $jam_keluar);
                                });
                        });
                })
                ->take($neededRooms) // Membatasi jumlah ruangan yang dibutuhkan
                ->get();

            // Membuat jadwal ruangan jika ada ruangan yang tersedia
            foreach ($availableRooms as $room) {
                JadwalRuangan::create([
                    'pemetaan_id' => $this->id,
                    'ruangan_id' => $room->id,
                    'jam_masuk' => $jam_masuk,
                    'jam_keluar' => $jam_keluar,
                    'hari' => $hari,
                ]);
            }
        } elseif ($jenis_ruangan === 'RK' || $jenis_ruangan === 'Seminar') {
            // Jika jenis ruangan RK atau Seminar, hanya butuh satu ruangan
            $roomType = $jenis_ruangan;

            $availableRooms = Ruangan::where('jenis_ruangan', $roomType)
                ->whereNotIn('id', function ($query) use ($jam_masuk, $jam_keluar, $hari) {
                    $query->select('ruangan_id')
                        ->from('jadwal_ruangans')
                        ->where('hari', $hari)
                        ->where(function ($query) use ($jam_masuk, $jam_keluar) {
                            $query->whereBetween('jam_masuk', [$jam_masuk, $jam_keluar])
                                ->orWhereBetween('jam_keluar', [$jam_masuk, $jam_keluar])
                                ->orWhere(function ($query) use ($jam_masuk, $jam_keluar) {
                                    $query->where('jam_masuk', '<=', $jam_masuk)
                                        ->where('jam_keluar', '>=', $jam_keluar);
                                });
                        });
                })
                ->first(); // Hanya butuh satu ruangan

            if ($availableRooms) {
                JadwalRuangan::create([
                    'pemetaan_id' => $this->id,
                    'ruangan_id' => $availableRooms->id,
                    'jam_masuk' => $jam_masuk,
                    'jam_keluar' => $jam_keluar,
                    'hari' => $hari,
                ]);
            }
        }
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



