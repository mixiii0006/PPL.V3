<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;

class Dosen extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'NIP',
        'NIDN',
        'Nama',
        'TTL',
        'CPNS',
        'Gol_Akhir',
        'Pendidikan_Akhir',
        'Jabfung_Akhir',
        'Homebase_PDDIKTI'
    ];

    public function mataKuliah(): BelongsToMany
    {
        return $this->belongsToMany(MataKuliah::class, 'pemetaans');
    }
}
