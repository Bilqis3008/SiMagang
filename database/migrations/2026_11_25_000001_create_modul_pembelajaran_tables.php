<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Modul Pembelajaran
        Schema::create('modul_pembelajaran', function (Blueprint $table) {
            $table->id('id_modul');
            $table->string('id_pembimbing');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            
            // Materi PDF
            $table->string('file_pdf')->nullable();
            
            // Video Modul: opsi upload video atau link yt
            $table->enum('tipe_video', ['upload', 'youtube'])->default('youtube');
            $table->string('video_url')->nullable(); // link youtube atau path file upload
            
            // Waktu Quiz (dalam menit)
            $table->integer('durasi_kuis')->default(15); // menit
            
            $table->timestamps();

            $table->foreign('id_pembimbing')->references('id_pembimbing')->on('pembimbing')->onDelete('cascade');
        });

        // 2. Soal Quiz Pilihan Ganda
        Schema::create('modul_soal', function (Blueprint $table) {
            $table->id('id_soal');
            $table->unsignedBigInteger('id_modul');
            $table->text('pertanyaan');
            $table->text('opsi_a');
            $table->text('opsi_b');
            $table->text('opsi_c');
            $table->text('opsi_d');
            $table->enum('kunci_jawaban', ['a', 'b', 'c', 'd']);
            $table->timestamps();

            $table->foreign('id_modul')->references('id_modul')->on('modul_pembelajaran')->onDelete('cascade');
        });

        // 3. Penugasan Modul ke Siswa
        Schema::create('modul_siswa', function (Blueprint $table) {
            $table->id('id_modul_siswa');
            $table->unsignedBigInteger('id_modul');
            $table->string('nisn');
            $table->string('id_pembimbing');
            
            // Status tahapan:
            // pdf_selesai: boolean
            // video_selesai: boolean
            // kuis_selesai: boolean
            $table->boolean('pdf_selesai')->default(false);
            $table->boolean('video_selesai')->default(false);
            $table->boolean('kuis_selesai')->default(false);
            $table->decimal('nilai_kuis', 5, 2)->nullable();
            $table->timestamp('kuis_dimulai_pada')->nullable();
            $table->timestamp('kuis_selesai_pada')->nullable();
            
            $table->timestamps();

            $table->foreign('id_modul')->references('id_modul')->on('modul_pembelajaran')->onDelete('cascade');
            $table->foreign('nisn')->references('nisn')->on('siswa')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modul_siswa');
        Schema::dropIfExists('modul_soal');
        Schema::dropIfExists('modul_pembelajaran');
    }
};
