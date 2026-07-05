<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('pengajuan_surat', 'deleted_at')) {
            Schema::table('pengajuan_surat', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('pengajuan_surat', 'deleted_at')) {
            Schema::table('pengajuan_surat', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
