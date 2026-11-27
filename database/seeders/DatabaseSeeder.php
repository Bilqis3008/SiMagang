<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat akun admin default
        Admin::create([
            'nama'     => 'Bilqis Nabila Ummami',
            'email'    => 'zuhrimubarak@gmail.com',
            'password' => 'admin123', // akan otomatis di-hash oleh casts di model
            'role'     => 'Admin',
        ]);
    }
}