<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesans', function (Blueprint $table) {
            $table->id();
            $table->string('nisn');
            $table->string('sender_role'); // 'guru' or 'pembimbing'
            $table->string('sender_id');
            $table->string('receiver_role'); // 'guru' or 'pembimbing'
            $table->string('receiver_id');
            $table->text('pesan');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('nisn')->references('nisn')->on('siswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesans');
    }
};
