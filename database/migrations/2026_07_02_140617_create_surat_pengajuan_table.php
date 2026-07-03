<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->enum('jenis', ['aktif', 'magang', 'rekomendasi']); // jenis surat yang diajukan

            // data form pengajuan
            $table->string('nama');
            $table->string('nim');
            $table->string('semester');
            $table->text('keterangan')->nullable();
            $table->string('lampiran')->nullable(); // path file di storage/app/public

            $table->enum('status', ['pending', 'diproses', 'selesai', 'ditolak'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat');
    }
};