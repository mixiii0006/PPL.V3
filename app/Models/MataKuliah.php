<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;

class MataKuliah extends Model
{

    use HasFactory, Notifiable;

    protected $fillable = [
        'nama_matakuliah',
        'tingkat',
        'tanggal_mulai',
        'tanggal_selesai'
    ];
    protected $table = 'mata_kuliahs';

   

    public function dosen(): BelongsToMany
    {
        return $this->belongsToMany(Dosen::class, 'pemetaans');
    }
}
