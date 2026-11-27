<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran';
    protected $primaryKey = 'id_tahun_ajaran';

    protected $fillable = [
        'tahun_ajaran',
        'tgl_mulai',
        'tgl_selesai',
        'status',
    ];

    public function siswas()
    {
        return $this->hasMany(Siswa::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }

    public function gurus()
    {
        return $this->hasMany(Guru::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }

    /**
     * Otomatis membuat dan menampilkan tahun ajaran:
     * Format: YYYY / YYYY+1 (misal: 2026 / 2027) untuk tahun ini dan 5 tahun ke belakang.
     */
    public static function getOtomatis()
    {
        $currentYear = (int) date('Y');
        for ($y = $currentYear; $y >= $currentYear - 5; $y--) {
            $nextY = $y + 1;
            $label = "{$y} / {$nextY}";
            $start = "{$y}-01-01";
            $end = "{$y}-12-31";

            $existing = static::where('tahun_ajaran', $label)
                ->orWhere('tahun_ajaran', "{$y}/{$nextY}")
                ->orWhere('tahun_ajaran', (string) $y)
                ->orWhere(function ($q) use ($start, $end) {
                    $q->where('tgl_mulai', $start)->where('tgl_selesai', $end);
                })
                ->orWhere('tahun_ajaran', 'like', "{$y}/%")
                ->first();

            if (!$existing) {
                static::create([
                    'tahun_ajaran' => $label,
                    'tgl_mulai'    => $start,
                    'tgl_selesai'  => $end,
                    'status'       => 'aktif',
                ]);
            } else {
                $existing->update([
                    'tahun_ajaran' => $label,
                    'tgl_mulai'   => $start,
                    'tgl_selesai' => $end,
                ]);
            }
        }

        return static::where(function ($q) use ($currentYear) {
            $q->whereYear('tgl_mulai', '>=', $currentYear - 5);
        })->orderBy('tgl_mulai', 'desc')->get();
    }

    public function getLabelPeriodeAttribute()
    {
        return $this->tahun_ajaran;
    }
}
