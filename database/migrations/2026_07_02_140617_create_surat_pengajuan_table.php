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
        Schema::create('pengajuan_surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->enum('jenis', ['aktif', 'magang', 'rekomendasi']); // jenis surat yang diajukan

            // 1. SURAT AKTIF KULIAH
            $table->string('nama');
            $table->string('nim');
            $table->string('semester');
            $table->text('keterangan')->nullable();
            $table->string('lampiran')->nullable(); 

            // 2. SURAT MAGANG
            $table->string('pimpinan_instansi')->nullable(); 
            $table->string('instansi_tujuan')->nullable();   
            $table->date('awal_magang')->nullable();         
            $table->date('akhir_magang')->nullable();        
            $table->string('email_mahasiswa')->nullable();   

            // 3. SURAT REKOMENDASI
            $table->string('nama_dosen')->nullable();        
            $table->string('nip_dosen')->nullable();          
            $table->string('jabatan_akademik')->nullable();   
            $table->string('fakultas')->nullable();          
            $table->string('tujuan_rekomendasi')->nullable(); 

            // STATUS
            $table->enum('status', ['pending', 'diproses', 'selesai', 'ditolak'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat');
    }
};