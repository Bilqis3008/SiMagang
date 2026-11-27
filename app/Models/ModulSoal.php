<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulSoal extends Model
{
    use HasFactory;

    protected $table = 'modul_soal';
    protected $primaryKey = 'id_soal';

    protected $fillable = [
        'id_modul',
        'pertanyaan',
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'kunci_jawaban',
    ];

    public function modul()
    {
        return $this->belongsTo(ModulPembelajaran::class, 'id_modul', 'id_modul');
    }
}
