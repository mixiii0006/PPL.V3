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
}
