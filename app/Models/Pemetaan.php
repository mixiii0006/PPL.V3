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
        'tanggal_mulai',
        'tanggal_selesai'

    ];

    // Define the relationship with Dosen
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
