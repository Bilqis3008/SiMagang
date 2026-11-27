<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulSiswa extends Model
{
    use HasFactory;

    protected $table = 'modul_siswa';
    protected $primaryKey = 'id_modul_siswa';

    protected $fillable = [
        'id_modul',
        'nisn',
        'id_pembimbing',
        'pdf_selesai',
        'video_selesai',
        'kuis_selesai',
        'nilai_kuis',
        'kuis_dimulai_pada',
        'kuis_selesai_pada',
        'tgl_mulai',
        'tgl_selesai',
    ];

    protected $casts = [
        'pdf_selesai' => 'boolean',
        'video_selesai' => 'boolean',
        'kuis_selesai' => 'boolean',
        'kuis_dimulai_pada' => 'datetime',
        'kuis_selesai_pada' => 'datetime',
    ];

    public function modul()
    {
        return $this->belongsTo(ModulPembelajaran::class, 'id_modul', 'id_modul');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }

    public function pembimbing()
    {
        return $this->belongsTo(Pembimbing::class, 'id_pembimbing', 'id_pembimbing');
    }
}
