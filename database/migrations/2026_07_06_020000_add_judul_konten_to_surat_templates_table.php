<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('surat_templates', function (Blueprint $table) {
            if (! Schema::hasColumn('surat_templates', 'judul')) {
                $table->string('judul')->nullable()->after('jenis');
            }
            if (! Schema::hasColumn('surat_templates', 'konten')) {
                $table->text('konten')->nullable()->after('judul');
            }
        });

        DB::table('surat_templates')
            ->whereNotNull('original_name')
            ->update(['judul' => DB::raw('original_name'), 'konten' => 'Template diunggah.']);
    }

    public function down()
    {
        Schema::table('surat_templates', function (Blueprint $table) {
            if (Schema::hasColumn('surat_templates', 'konten')) {
                $table->dropColumn('konten');
            }
            if (Schema::hasColumn('surat_templates', 'judul')) {
                $table->dropColumn('judul');
            }
        });
    }
};
