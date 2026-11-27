<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulPembelajaran extends Model
{
    use HasFactory;

    protected $table = 'modul_pembelajaran';
    protected $primaryKey = 'id_modul';

    protected $fillable = [
        'id_pembimbing',
        'judul',
        'deskripsi',
        'file_pdf',
        'tipe_video',
        'video_url',
        'durasi_kuis',
    ];

    public function pembimbing()
    {
        return $this->belongsTo(Pembimbing::class, 'id_pembimbing', 'id_pembimbing');
    }

    public function soals()
    {
        return $this->hasMany(ModulSoal::class, 'id_modul', 'id_modul');
    }

    public function modulSiswas()
    {
        return $this->hasMany(ModulSiswa::class, 'id_modul', 'id_modul');
    }
}
