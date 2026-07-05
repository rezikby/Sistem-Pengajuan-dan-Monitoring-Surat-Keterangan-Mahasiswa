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
            if (! Schema::hasColumn('pengajuan_surat', 'nomor_surat')) {
                $table->string('nomor_surat', 20)->nullable()->after('surat_file');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            if (Schema::hasColumn('pengajuan_surat', 'nomor_surat')) {
                $table->dropColumn('nomor_surat');
            }
        });
    }
};
