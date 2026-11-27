<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformasiDashboard extends Model
{
    protected $table = 'informasi_dashboard';

    protected $fillable = [
        'visi',
        'misi',
        'sejarah',
        'jam_operasional',
        'deskripsi_jam_operasional',
        'alamat_lokasi',
        'link_maps',
        'email_kontak',
        'telp_kontak',
        'website_kontak',
        'nama_fakultas',
        'deskripsi_banner',
    ];

    /**
     * Ambil satu-satunya record (singleton pattern).
     */
    public static function getInstance(): self
    {
        $info = self::first();
        if (!$info) {
            $info = self::create([
                'visi'                    => '',
                'misi'                    => json_encode([]),
                'sejarah'                 => '',
                'jam_operasional'         => 'Senin – Jumat: 08.00 – 16.00 WIB',
                'deskripsi_jam_operasional' => 'Sistem monitoring dapat diakses 24 jam.',
                'alamat_lokasi'           => 'Kantor BPS Kabupaten Muara Enim.',
                'link_maps'               => 'https://maps.google.com/?q=BPS+Kabupaten+Muara+Enim',
                'email_kontak'            => 'bps1603@bps.go.id',
                'telp_kontak'             => '(0734) 421147',
                'website_kontak'          => 'muaraenimkab.bps.go.id',
                'nama_fakultas'           => 'BPS Kabupaten Muara Enim',
                'deskripsi_banner'        => '',
            ]);
        }
        return $info;
    }

    /**
     * Decode misi dari JSON ke array.
     */
    public function getMisiArrayAttribute(): array
    {
        if (empty($this->misi)) return [];
        $decoded = json_decode($this->misi, true);
        return is_array($decoded) ? $decoded : [];
    }
}
