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
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            if (! Schema::hasColumn('pengajuan_surat', 'pimpinan_instansi')) {
                $table->string('pimpinan_instansi')->nullable()->after('keterangan');
            }
            if (! Schema::hasColumn('pengajuan_surat', 'instansi_tujuan')) {
                $table->string('instansi_tujuan')->nullable()->after('pimpinan_instansi');
            }
            if (! Schema::hasColumn('pengajuan_surat', 'awal_magang')) {
                $table->date('awal_magang')->nullable()->after('instansi_tujuan');
            }
            if (! Schema::hasColumn('pengajuan_surat', 'akhir_magang')) {
                $table->date('akhir_magang')->nullable()->after('awal_magang');
            }
            if (! Schema::hasColumn('pengajuan_surat', 'email_mahasiswa')) {
                $table->string('email_mahasiswa')->nullable()->after('akhir_magang');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            $table->dropColumn([
                'pimpinan_instansi',
                'instansi_tujuan',
                'awal_magang',
                'akhir_magang',
                'email_mahasiswa',
            ]);
        });
    }
};