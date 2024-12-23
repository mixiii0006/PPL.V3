<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Ruangan extends Model
{

    use HasFactory, Notifiable;

    protected $fillable = [
        'nama_ruangan',
        'kapasitas',
        'fasilitas'
    ];

    public function jadwalRuangans(): HasMany
    {
        return $this->hasMany(JadwalRuangan::class);
    }

    public function jadwalRuangan()
    {
        return $this->hasMany(JadwalRuangan::class, 'ruangan_id');
    }

    public static function boot()
    {
        parent::boot();

        // Hapus jadwal ruangan saat ruangan dihapus
        static::deleting(function ($ruangan) {
            // Hapus jadwal yang terkait dengan ruangan
            $ruangan->jadwalRuangans->each(function ($jadwal) {
                $jadwal->delete();
            });
        });
    }
}
