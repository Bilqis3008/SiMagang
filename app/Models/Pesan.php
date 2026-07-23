<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'nisn',
        'sender_role',
        'sender_id',
        'receiver_role',
        'receiver_id',
        'pesan',
        'is_read',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }
}
