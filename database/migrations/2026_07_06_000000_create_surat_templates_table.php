<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('surat_templates')) {
            Schema::create('surat_templates', function (Blueprint $table) {
                $table->id();
                $table->string('jenis');
                $table->string('original_name');
                $table->string('file_path');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('surat_templates');
    }
};
