<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (Schema::hasTable('pengajuan_surat') && !Schema::hasColumn('pengajuan_surat', 'surat_file')) {
            Schema::table('pengajuan_surat', function (Blueprint $table) {
                $table->string('surat_file')->nullable()->after('lampiran');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('pengajuan_surat') && Schema::hasColumn('pengajuan_surat', 'surat_file')) {
            Schema::table('pengajuan_surat', function (Blueprint $table) {
                $table->dropColumn('surat_file');
            });
        }
    }
};
